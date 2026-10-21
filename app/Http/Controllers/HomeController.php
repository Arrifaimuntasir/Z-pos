<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Branch;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (auth()->user()->hasRole('Super Admin') && !auth()->user()->shop_id) {
            return redirect()->route('superadmin.shops.index');
        }

        // Remember the chosen period so returning to the dashboard from another page
        // (plain /home link) keeps it until the user picks a different one.
        $requestedFilter = $request->query('filter');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $requestedFilter = 'custom';
            session([
                'dashboard_filter' => 'custom',
                'dashboard_start_date' => $request->query('start_date'),
                'dashboard_end_date' => $request->query('end_date'),
            ]);
        } elseif ($requestedFilter === 'custom') {
            session(['dashboard_filter' => 'custom']);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                session([
                    'dashboard_start_date' => $request->query('start_date'),
                    'dashboard_end_date' => $request->query('end_date'),
                ]);
            }
        } elseif (in_array($requestedFilter, ['overall', 'today', 'month', 'half_year', 'year'], true)) {
            session(['dashboard_filter' => $requestedFilter]);
        }
        $filter = session('dashboard_filter', 'overall');
        $startDate = null;
        $endDate = null;
        $customStartDate = session('dashboard_start_date', now()->startOfMonth()->format('Y-m-d'));
        $customEndDate = session('dashboard_end_date', now()->format('Y-m-d'));

        if ($filter === 'today') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($filter === 'month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($filter === 'half_year') {
            $startDate = now()->subMonths(6)->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($filter === 'year') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        } elseif ($filter === 'custom') {
            try {
                $startDate = \Carbon\Carbon::parse($customStartDate)->startOfDay();
                $endDate = \Carbon\Carbon::parse($customEndDate)->endOfDay();
            } catch (\Exception $e) {
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
            }
        }

        $branchId = $this->getActiveBranchId();
        if ($branchId && !Branch::where('id', $branchId)->exists()) {
            $branchId = null;
        }

        $isAdmin = auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin');

        // Cashier switching capability for shop owners / administrators
        $cashiers = collect();
        $selectedCashierId = null;
        $selectedCashier = null;

        if ($isAdmin) {
            if ($request->has('cashier_id')) {
                $reqCashier = $request->query('cashier_id');
                if ($reqCashier === 'all' || empty($reqCashier)) {
                    session()->forget('dashboard_cashier_id');
                } else {
                    session(['dashboard_cashier_id' => (int)$reqCashier]);
                }
            }

            $savedCashierId = session('dashboard_cashier_id');
            if ($savedCashierId) {
                $selectedCashier = User::where('shop_id', auth()->user()->shop_id)
                    ->where('id', $savedCashierId)
                    ->first();
                if ($selectedCashier) {
                    $selectedCashierId = $selectedCashier->id;
                } else {
                    session()->forget('dashboard_cashier_id');
                }
            }

            // Everyone in the shop, including the owner himself, so he can also view his own sales.
            // The owner is listed first, then the rest alphabetically.
            $cashiers = User::where('shop_id', auth()->user()->shop_id)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get()
                ->sortBy(fn ($u) => $u->id === auth()->id() ? 0 : 1)
                ->values();
        }

        // Determine effective user for sales filtering
        $effectiveUserId = null;
        if (!$isAdmin) {
            $effectiveUserId = auth()->id();
        } elseif ($selectedCashierId) {
            $effectiveUserId = $selectedCashierId;
        }

        // Pro-forma sales are draft quotes — not real revenue/profit until converted (markAsPaid).
        $salesQuery = Sale::query()
            ->where('payment_status', '!=', 'proforma')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->where('user_id', $effectiveUserId);
            });

        $profitQuery = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.payment_status', '!=', 'proforma')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->where('sales.user_id', $effectiveUserId);
            });
            
        $expenseQuery = Expense::query()
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

        $returnsQuery = SaleReturn::query()
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->whereHas('sale', function($sq) use ($effectiveUserId) {
                    $sq->where('user_id', $effectiveUserId);
                });
            });

        $returnProfitQuery = SaleReturnItem::query()
            ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
            ->join('sale_items', 'sale_return_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_returns.shop_id', auth()->user()->shop_id)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->whereHas('saleReturn.sale', function($sq) use ($effectiveUserId) {
                    $sq->where('user_id', $effectiveUserId);
                });
            });

        // Defective returns not yet repaired are a real loss (cost paid, nothing sellable) —
        // not just "no profit" like a good-condition return.
        $defectiveLossQuery = SaleReturnItem::query()
            ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
            ->join('sale_items', 'sale_return_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_returns.shop_id', auth()->user()->shop_id)
            ->where('sale_return_items.condition', 'defective')
            ->where('sale_return_items.repair_status', 'not_repaired')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->whereHas('saleReturn.sale', function($sq) use ($effectiveUserId) {
                    $sq->where('user_id', $effectiveUserId);
                });
            });

        if ($startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate, $endDate]);
            $profitQuery->whereBetween('sales.sale_date', [$startDate, $endDate]);
            $expenseQuery->whereBetween('expense_date', [$startDate, $endDate]);
            $returnsQuery->whereBetween('return_date', [$startDate, $endDate]);
            $returnProfitQuery->whereBetween('sale_returns.return_date', [$startDate, $endDate]);
            $defectiveLossQuery->whereBetween('sale_returns.return_date', [$startDate, $endDate]);
        }

        // Sales count & total refunds
        $salesCount = (clone $salesQuery)->count();
        $returnsTotal = (clone $returnsQuery)->sum('total_refund');

        // Revenue is total value of sales minus refunds
        $totalSales = (clone $salesQuery)->sum('total_amount') - $returnsTotal;

        // Calculate Gross Profit from Sale Items
        $grossProfitGross = (clone $profitQuery)
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        $returnProfit = (clone $returnProfitQuery)
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_return_items.quantity) as profit')
            ->value('profit') ?? 0;

        $defectiveLoss = (clone $defectiveLossQuery)
            ->selectRaw('SUM(sale_items.unit_cost * sale_return_items.quantity) as loss')
            ->value('loss') ?? 0;

        // Standalone damaged stock write-offs (DefectiveStock)
        $damagedStockLoss = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('defective_stocks') && !$selectedCashierId) {
            $damagedStockQuery = \App\Models\DefectiveStock::query()
                ->join('products', 'defective_stocks.product_id', '=', 'products.id')
                ->where('defective_stocks.shop_id', auth()->user()->shop_id)
                ->where(function($q) {
                    $q->where('defective_stocks.status', 'defective')
                      ->orWhereNull('defective_stocks.status');
                })
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('defective_stocks.branch_id', $branchId);
                });

            if ($startDate && $endDate) {
                $damagedStockQuery->whereBetween('defective_stocks.created_at', [$startDate, $endDate]);
            }

            $damagedStockLoss = $damagedStockQuery->selectRaw('SUM(defective_stocks.quantity * products.cost_price) as loss')
                ->value('loss') ?? 0;
        }

        $grossProfit = $grossProfitGross - $returnProfit - $defectiveLoss - $damagedStockLoss;

        // Total Expense is just the general expenses
        $totalExpense = $expenseQuery->sum('amount');
        
        $netProfit = $grossProfit - $totalExpense;

        // Get Recent Sales
        $recentSales = Sale::with('customer')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->where('user_id', $effectiveUserId);
            })
            ->latest()->take(5)->get();

        // Top products for period
        $topProducts = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.payment_status', '!=', 'proforma')
            ->where('sales.shop_id', auth()->user()->shop_id)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->where('sales.user_id', $effectiveUserId);
            })
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('sales.sale_date', [$startDate, $endDate]);
            })
            ->selectRaw('products.name, SUM(sale_items.quantity) as total_qty, SUM(sale_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Generate Monthly Data for Charts
        $monthlyIncome = array_fill(0, 12, 0); 
        $monthlyExpense = array_fill(0, 12, 0);
        $monthlyNetCash = array_fill(0, 12, 0);

        $currentYear = date('Y');

        // Fetch income (paid amount of sales) grouped by month
        $incomeByMonth = Sale::whereYear('sale_date', $currentYear)
            ->where('payment_status', '!=', 'proforma')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->where('user_id', $effectiveUserId);
            })
            ->selectRaw('MONTH(sale_date) as month, SUM(paid_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // Fetch returns grouped by month
        $returnsByMonth = SaleReturn::whereYear('return_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($effectiveUserId, function($q) use ($effectiveUserId) {
                $q->whereHas('sale', function($sq) use ($effectiveUserId) {
                    $sq->where('user_id', $effectiveUserId);
                });
            })
            ->selectRaw('MONTH(return_date) as month, SUM(total_refund) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // Fetch general expenses grouped by month (shop-wide or by branch)
        $expensesByMonth = Expense::whereYear('expense_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();
            
        // Fetch purchases grouped by month
        $purchasesByMonth = Purchase::whereYear('purchase_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->selectRaw('MONTH(purchase_date) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $inc = ($incomeByMonth[$i] ?? 0) - ($returnsByMonth[$i] ?? 0);
            $exp = ($selectedCashierId ? 0 : (($expensesByMonth[$i] ?? 0) + ($purchasesByMonth[$i] ?? 0)));
            
            $monthlyIncome[$i-1] = $inc;
            $monthlyExpense[$i-1] = $exp;
            $monthlyNetCash[$i-1] = $inc - $exp;
        }

        // Fetch Top Expenses Data (for whole shop overview)
        $topExpensesRaw = [];
        $topExpenseLabels = [];
        $topExpenseData = [];
        $totalPurchases = 0;

        if (!$selectedCashierId) {
            $topExpensesQuery = Expense::query()
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });

            if ($startDate && $endDate) {
                $topExpensesQuery->whereBetween('expense_date', [$startDate, $endDate]);
            }

            $topExpensesRaw = $topExpensesQuery
                ->selectRaw('category, SUM(amount) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(4)
                ->pluck('total', 'category')->toArray();

            $purchasesQuery = Purchase::query()
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });

            if ($startDate && $endDate) {
                if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                    $purchasesQuery->whereDate('purchase_date', $startDate->format('Y-m-d'));
                } else {
                    $purchasesQuery->whereDate('purchase_date', '>=', $startDate->format('Y-m-d'))
                                   ->whereDate('purchase_date', '<=', $endDate->format('Y-m-d'));
                }
            }

            $totalPurchases = (float) $purchasesQuery->sum('total_amount');
            if ($totalPurchases > 0) {
                $topExpensesRaw[__('Purchases')] = $totalPurchases;
            }

            arsort($topExpensesRaw);
            $topExpensesRaw = array_slice($topExpensesRaw, 0, 4);
            
            $topExpenseLabels = array_keys($topExpensesRaw);
            foreach($topExpenseLabels as $k => $v) {
                if (empty($v)) $topExpenseLabels[$k] = __('Uncategorized');
            }
            $topExpenseData = array_values($topExpensesRaw);
        }

        return view('home', compact(
            'totalSales', 'totalPurchases', 'grossProfit', 'netProfit', 'totalExpense', 'defectiveLoss', 'damagedStockLoss', 'recentSales',
            'monthlyIncome', 'monthlyExpense', 'monthlyNetCash', 'topExpenseLabels', 'topExpenseData', 'filter', 'isAdmin',
            'cashiers', 'selectedCashierId', 'selectedCashier', 'salesCount', 'returnsTotal', 'topProducts',
            'customStartDate', 'customEndDate'
        ));
    }
}
