<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $filter = $request->query('filter', 'overall');
        $startDate = null;
        $endDate = null;

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
        }

        $branchId = auth()->user()->branch_id ?: session('active_branch_id');
        if ($branchId && !\App\Models\Branch::where('id', $branchId)->exists()) {
            $branchId = null;
        }

        $salesQuery = \App\Models\Sale::query()
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
            
        $profitQuery = \App\Models\SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            });
            
        $expenseQuery = \App\Models\Expense::query()
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

        if ($startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate, $endDate]);
            $profitQuery->whereBetween('sales.sale_date', [$startDate, $endDate]);
            $expenseQuery->whereBetween('expense_date', [$startDate, $endDate]);
        }

        // Revenue is total value of sales. Income is total amount paid.
        $totalSales = $salesQuery->sum('total_amount');
        
        // Calculate Gross Profit from Sale Items
        $grossProfit = $profitQuery
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        // Total Expense is just the general expenses now
        $totalExpense = $expenseQuery->sum('amount');
        
        $netProfit = $grossProfit - $totalExpense;

        // Get Recent Sales
        $recentSales = \App\Models\Sale::with('customer')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->latest()->take(5)->get();

        // Generate Monthly Data for Charts
        $monthlyIncome = array_fill(0, 12, 0); 
        $monthlyExpense = array_fill(0, 12, 0);
        $monthlyNetCash = array_fill(0, 12, 0);

        $currentYear = date('Y');

        // Fetch income (paid amount of sales) grouped by month
        $incomeByMonth = \App\Models\Sale::whereYear('sale_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->selectRaw('MONTH(sale_date) as month, SUM(paid_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // Fetch general expenses grouped by month
        $expensesByMonth = \App\Models\Expense::whereYear('expense_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();
            
        // Fetch purchases grouped by month
        $purchasesByMonth = \App\Models\Purchase::whereYear('purchase_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->selectRaw('MONTH(purchase_date) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $inc = $incomeByMonth[$i] ?? 0;
            $exp = ($expensesByMonth[$i] ?? 0) + ($purchasesByMonth[$i] ?? 0);
            
            $monthlyIncome[$i-1] = $inc;
            $monthlyExpense[$i-1] = $exp;
            $monthlyNetCash[$i-1] = $inc - $exp;
        }

        // Fetch Top Expenses Data
        $topExpensesRaw = \App\Models\Expense::whereYear('expense_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(4)
            ->pluck('total', 'category')->toArray();

        $totalPurchases = \App\Models\Purchase::whereYear('purchase_date', $currentYear)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('total_amount');
        if ($totalPurchases > 0) {
            $topExpensesRaw['Purchases'] = $totalPurchases;
        }

        // Sort combined array and get top 4
        arsort($topExpensesRaw);
        $topExpensesRaw = array_slice($topExpensesRaw, 0, 4);
        
        $topExpenseLabels = array_keys($topExpensesRaw);
        // Replace empty category with "Uncategorized"
        foreach($topExpenseLabels as $k => $v) {
            if (empty($v)) $topExpenseLabels[$k] = 'Uncategorized';
        }
        $topExpenseData = array_values($topExpensesRaw);

        return view('home', compact(
            'totalSales', 'grossProfit', 'netProfit', 'totalExpense', 'recentSales',
            'monthlyIncome', 'monthlyExpense', 'monthlyNetCash', 'topExpenseLabels', 'topExpenseData', 'filter'
        ));
    }
}
