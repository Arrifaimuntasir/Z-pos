<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index', $this->overviewData());
    }

    public function indexPdf()
    {
        $pdf = Pdf::loadView('pdf.reports.overview', $this->overviewData() + $this->pdfMeta());

        return $pdf->download('ZPOS_Report_Overview_' . now()->format('Y-m-d') . '.pdf');
    }

    private function overviewData(): array
    {
        $branchId = $this->getActiveBranchId();
        $shopId = auth()->user()->shop_id;
        $hasBranches = \App\Models\Branch::where('shop_id', $shopId)->exists();

        if ($hasBranches) {
            $totalProducts = DB::table('branch_product')
                ->join('products', 'branch_product.product_id', '=', 'products.id')
                ->where('products.shop_id', $shopId)
                ->whereNull('products.deleted_at')
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('branch_product.branch_id', $branchId);
                })
                ->distinct('products.id')
                ->count('products.id');

            $totalStockValue = DB::table('branch_product')
                ->join('products', 'branch_product.product_id', '=', 'products.id')
                ->where('products.shop_id', $shopId)
                ->whereNull('products.deleted_at')
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('branch_product.branch_id', $branchId);
                })
                ->sum(DB::raw('products.cost_price * branch_product.quantity'));

            $totalSalesValue = DB::table('branch_product')
                ->join('products', 'branch_product.product_id', '=', 'products.id')
                ->where('products.shop_id', $shopId)
                ->whereNull('products.deleted_at')
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('branch_product.branch_id', $branchId);
                })
                ->sum(DB::raw('products.selling_price * branch_product.quantity'));
        } else {
            $totalProducts = Product::where('shop_id', $shopId)
                ->whereNull('deleted_at')
                ->count();

            $totalStockValue = Product::where('shop_id', $shopId)
                ->whereNull('deleted_at')
                ->sum(DB::raw('cost_price * stock'));

            $totalSalesValue = Product::where('shop_id', $shopId)
                ->whereNull('deleted_at')
                ->sum(DB::raw('selling_price * stock'));
        }

        $totalExpenses = Expense::when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('amount');

        // Pro-forma sales are draft quotes — not real revenue/profit until converted (markAsPaid).
        $totalSalesReal = \App\Models\Sale::where('payment_status', '!=', 'proforma')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('total_amount');

        $grossProfitGross = \App\Models\SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.payment_status', '!=', 'proforma')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            })
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        $returnProfit = \App\Models\SaleReturnItem::query()
            ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
            ->join('sale_items', 'sale_return_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_returns.shop_id', $shopId)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_return_items.quantity) as profit')
            ->value('profit') ?? 0;

        // Defective returns not yet repaired are a real loss (cost paid, nothing sellable) —
        // not just "no profit" like a good-condition return.
        $defectiveLoss = \App\Models\SaleReturnItem::query()
            ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
            ->join('sale_items', 'sale_return_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_returns.shop_id', $shopId)
            ->where('sale_return_items.condition', 'defective')
            ->where('sale_return_items.repair_status', 'not_repaired')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->selectRaw('SUM(sale_items.unit_cost * sale_return_items.quantity) as loss')
            ->value('loss') ?? 0;

        $defectiveReturnQty = (float) (\App\Models\SaleReturnItem::query()
            ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
            ->where('sale_returns.shop_id', $shopId)
            ->where('sale_return_items.condition', 'defective')
            ->where('sale_return_items.repair_status', 'not_repaired')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->sum('sale_return_items.quantity') ?? 0);

        $grossProfit = $grossProfitGross - $returnProfit - $defectiveLoss;

        $potentialProfit = $totalSalesValue - $totalStockValue - $totalExpenses;

        $totalPurchases = \App\Models\Purchase::when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('total_amount');

        $totalRefunds = \App\Models\SaleReturn::where('shop_id', $shopId)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('total_refund');

        // Standalone "Damaged Stock" write-offs (removed from inventory before ever being sold).
        $damagedStockQty = 0;
        $damagedStockLoss = 0;
        $recentShopDamaged = collect();

        if (\Illuminate\Support\Facades\Schema::hasTable('defective_stocks')) {
            $damagedStockQty = (float) \App\Models\DefectiveStock::where('shop_id', $shopId)
                ->where(function($q) {
                    $q->where('status', 'defective')->orWhereNull('status');
                })
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })->sum('quantity');

            $damagedStockLoss = (float) (\App\Models\DefectiveStock::query()
                ->join('products', 'defective_stocks.product_id', '=', 'products.id')
                ->where('defective_stocks.shop_id', $shopId)
                ->where(function($q) {
                    $q->where('defective_stocks.status', 'defective')->orWhereNull('defective_stocks.status');
                })
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('defective_stocks.branch_id', $branchId);
                })
                ->selectRaw('SUM(defective_stocks.quantity * products.cost_price) as loss')
                ->value('loss') ?? 0);

            $recentShopDamaged = \App\Models\DefectiveStock::with(['product.unit', 'branch', 'user'])
                ->where('shop_id', $shopId)
                ->where(function($q) {
                    $q->where('status', 'defective')->orWhereNull('status');
                })
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->latest()
                ->take(8)
                ->get()
                ->map(function($rec) {
                    $cost = (float) ($rec->product->cost_price ?? 0);
                    return (object) [
                        'id' => 'ds_' . $rec->id,
                        'source_type' => 'shop_damaged',
                        'source_label' => 'Zilizoharibika Dukani',
                        'created_at' => $rec->created_at,
                        'product' => $rec->product,
                        'quantity' => (float) $rec->quantity,
                        'unit_cost' => $cost,
                        'loss' => (float) ($rec->quantity * $cost),
                        'reason' => $rec->reason ?? 'Imeharibika Dukani/Stoo',
                        'user_name' => $rec->user->name ?? 'Admin',
                        'branch_name' => $rec->branch->name ?? 'Main',
                    ];
                });
        }

        // Recent defective returns from customers
        $recentDefectiveReturns = \App\Models\SaleReturnItem::with(['saleReturn.user', 'saleReturn.branch', 'saleItem.product.unit', 'product.unit'])
            ->whereHas('saleReturn', function($q) use ($shopId, $branchId) {
                $q->where('shop_id', $shopId);
                if ($branchId) $q->where('branch_id', $branchId);
            })
            ->where('condition', 'defective')
            ->latest()
            ->take(8)
            ->get()
            ->map(function($item) {
                $product = $item->product ?? ($item->saleItem->product ?? null);
                $unitCost = (float) ($item->saleItem->unit_cost ?? ($product->cost_price ?? 0));
                return (object) [
                    'id' => 'ret_' . $item->id,
                    'source_type' => 'customer_return',
                    'source_label' => 'Rejesho Mbovu',
                    'created_at' => $item->created_at ?? ($item->saleReturn->return_date ?? now()),
                    'product' => $product,
                    'quantity' => (float) $item->quantity,
                    'unit_cost' => $unitCost,
                    'loss' => (float) ($item->quantity * $unitCost),
                    'reason' => $item->reason ?? 'Rejesho Mbovu (Defective Return)',
                    'user_name' => optional(optional($item->saleReturn)->user)->name ?? (optional(optional($item->saleReturn)->sale)->user->name ?? 'Staff'),
                    'branch_name' => optional(optional($item->saleReturn)->branch)->name ?? 'Main',
                ];
            });

        $recentDamagedStocks = $recentShopDamaged->concat($recentDefectiveReturns)->sortByDesc('created_at')->take(8)->values();
        $totalDefectiveLoss = $defectiveLoss + $damagedStockLoss;
        $totalDefectiveQty = $damagedStockQty + $defectiveReturnQty;

        // --- COMPREHENSIVE CATEGORY BREAKDOWN ---
        if ($hasBranches) {
            $stockQuery = DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('branch_product', function($join) use ($branchId) {
                    $join->on('products.id', '=', 'branch_product.product_id');
                    if ($branchId) {
                        $join->where('branch_product.branch_id', '=', $branchId);
                    }
                })
                ->where('products.shop_id', $shopId)
                ->whereNull('products.deleted_at')
                ->groupBy('categories.id', 'categories.name')
                ->selectRaw('
                    COALESCE(categories.id, 0) as category_id,
                    COALESCE(categories.name, "Uncategorized") as category_name,
                    COUNT(DISTINCT products.id) as product_count,
                    SUM(COALESCE(branch_product.quantity, 0)) as total_stock,
                    SUM(products.cost_price * COALESCE(branch_product.quantity, 0)) as stock_cost,
                    SUM(products.selling_price * COALESCE(branch_product.quantity, 0)) as stock_selling
                ')
                ->get()
                ->keyBy('category_id');
        } else {
            $stockQuery = DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.shop_id', $shopId)
                ->whereNull('products.deleted_at')
                ->groupBy('categories.id', 'categories.name')
                ->selectRaw('
                    COALESCE(categories.id, 0) as category_id,
                    COALESCE(categories.name, "Uncategorized") as category_name,
                    COUNT(DISTINCT products.id) as product_count,
                    SUM(COALESCE(products.stock, 0)) as total_stock,
                    SUM(products.cost_price * COALESCE(products.stock, 0)) as stock_cost,
                    SUM(products.selling_price * COALESCE(products.stock, 0)) as stock_selling
                ')
                ->get()
                ->keyBy('category_id');
        }

        $salesByCategory = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.shop_id', $shopId)
            ->where('sales.payment_status', '!=', 'proforma')
            ->when($branchId, fn($q) => $q->where('sales.branch_id', $branchId))
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('
                COALESCE(categories.id, 0) as category_id,
                COALESCE(categories.name, "Uncategorized") as category_name,
                SUM(sale_items.quantity) as sold_qty,
                SUM(sale_items.quantity * sale_items.unit_price) as total_revenue,
                SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as gross_profit
            ')
            ->get()
            ->keyBy('category_id');

        $definedCategories = \App\Models\Category::where('shop_id', $shopId)->pluck('name', 'id');

        $allCategoryIds = collect($stockQuery->keys())
            ->merge($salesByCategory->keys())
            ->merge($definedCategories->keys())
            ->unique();

        $categoryBreakdown = [];
        $totalCategoryRevenue = 0;

        foreach ($allCategoryIds as $catId) {
            $stockData = $stockQuery->get($catId);
            $salesData = $salesByCategory->get($catId);

            $name = $definedCategories->get($catId) 
                ?? ($stockData->category_name ?? ($salesData->category_name ?? __('Uncategorized')));
            if ($catId == 0 || $name === 'Uncategorized') {
                $name = __('Uncategorized');
            }

            $productCount = (int) ($stockData->product_count ?? 0);
            $totalStock = (float) ($stockData->total_stock ?? 0);
            $stockCost = (float) ($stockData->stock_cost ?? 0);
            $stockSelling = (float) ($stockData->stock_selling ?? 0);
            $catPotentialProfit = max(0, $stockSelling - $stockCost);

            $soldQty = (float) ($salesData->sold_qty ?? 0);
            $revenue = (float) ($salesData->total_revenue ?? 0);
            $profit = (float) ($salesData->gross_profit ?? 0);
            $margin = $revenue > 0 ? round(($profit / $revenue) * 100, 1) : 0;

            $totalCategoryRevenue += $revenue;

            $categoryBreakdown[] = (object) [
                'id' => $catId,
                'name' => $name,
                'product_count' => $productCount,
                'total_stock' => $totalStock,
                'stock_cost' => $stockCost,
                'stock_selling' => $stockSelling,
                'potential_profit' => $catPotentialProfit,
                'sold_qty' => $soldQty,
                'revenue' => $revenue,
                'profit' => $profit,
                'margin' => $margin,
                'revenue_share' => 0,
            ];
        }

        foreach ($categoryBreakdown as $cat) {
            $cat->revenue_share = $totalCategoryRevenue > 0 ? round(($cat->revenue / $totalCategoryRevenue) * 100, 1) : 0;
        }

        usort($categoryBreakdown, function($a, $b) {
            if ($b->revenue != $a->revenue) return $b->revenue <=> $a->revenue;
            return $b->stock_selling <=> $a->stock_selling;
        });

        $topCategory = $categoryBreakdown[0] ?? null;
        $highestMarginCategory = collect($categoryBreakdown)->where('revenue', '>', 0)->sortByDesc('margin')->first();
        $highestStockCategory = collect($categoryBreakdown)->sortByDesc('stock_cost')->first();

        // Business Type specific analytics
        $businessType = auth()->user()->shop->business_type ?? 'Supermarket / Grocery';
        $expiringProductsCount = \App\Models\Product::where('shop_id', $shopId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->count();

        $expiredProductsCount = \App\Models\Product::where('shop_id', $shopId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->count();

        $imeiTrackedCount = \App\Models\Product::where('shop_id', $shopId)
            ->where('requires_imei', true)
            ->count();

        return compact(
            'totalProducts',
            'totalStockValue',
            'totalSalesValue',
            'totalExpenses',
            'potentialProfit',
            'totalSalesReal',
            'grossProfit',
            'defectiveLoss',
            'totalPurchases',
            'totalRefunds',
            'damagedStockQty',
            'damagedStockLoss',
            'defectiveReturnQty',
            'totalDefectiveQty',
            'totalDefectiveLoss',
            'recentDamagedStocks',
            'categoryBreakdown',
            'topCategory',
            'highestMarginCategory',
            'highestStockCategory',
            'businessType',
            'expiringProductsCount',
            'expiredProductsCount',
            'imeiTrackedCount'
        );
    }

    public function profitLoss(Request $request)
    {
        return view('reports.profit_loss', $this->profitLossData($request));
    }

    public function profitLossPdf(Request $request)
    {
        $data = $this->profitLossData($request);
        $pdf = Pdf::loadView('pdf.reports.profit_loss', $data + $this->pdfMeta());

        return $pdf->download('ZPOS_Profit_Loss_' . $data['startDate'] . '_to_' . $data['endDate'] . '.pdf');
    }

    private function profitLossData(Request $request): array
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());
        $branchId = $this->getActiveBranchId();

        // Pro-forma sales are draft quotes — not real revenue/profit until converted (markAsPaid).
        $grossProfitGross = \App\Models\SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.payment_status', '!=', 'proforma')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            })
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        $returnProfit = \App\Models\SaleReturnItem::query()
            ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
            ->join('sale_items', 'sale_return_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_returns.shop_id', auth()->user()->shop_id)
            ->whereBetween('sale_returns.return_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_return_items.quantity) as profit')
            ->value('profit') ?? 0;

        // Defective returns not yet repaired are a real loss (cost paid, nothing sellable) —
        // not just "no profit" like a good-condition return.
        $defectiveLoss = \App\Models\SaleReturnItem::query()
            ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
            ->join('sale_items', 'sale_return_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_returns.shop_id', auth()->user()->shop_id)
            ->where('sale_return_items.condition', 'defective')
            ->where('sale_return_items.repair_status', 'not_repaired')
            ->whereBetween('sale_returns.return_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->selectRaw('SUM(sale_items.unit_cost * sale_return_items.quantity) as loss')
            ->value('loss') ?? 0;

        // Standalone "Damaged Stock" write-offs (removed from inventory before ever being sold).
        // Records marked "good" went back into stock, so only still-defective ones count as loss.
        $damagedStockLoss = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('defective_stocks')) {
            $damagedStockLoss = \App\Models\DefectiveStock::query()
                ->join('products', 'defective_stocks.product_id', '=', 'products.id')
                ->where('defective_stocks.shop_id', auth()->user()->shop_id)
                ->where(function($q) {
                    $q->where('defective_stocks.status', 'defective')->orWhereNull('defective_stocks.status');
                })
                ->whereDate('defective_stocks.created_at', '>=', $startDate)
                ->whereDate('defective_stocks.created_at', '<=', $endDate)
                ->when($branchId, function($q) use ($branchId) {
                    $q->where('defective_stocks.branch_id', $branchId);
                })
                ->selectRaw('SUM(defective_stocks.quantity * products.cost_price) as loss')
                ->value('loss') ?? 0;
        }

        $grossProfit = $grossProfitGross - $returnProfit - $defectiveLoss - $damagedStockLoss;

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('amount');

        $netProfit = $grossProfit - $totalExpenses;

        return compact('startDate', 'endDate', 'grossProfit', 'totalExpenses', 'netProfit', 'defectiveLoss', 'damagedStockLoss');
    }

    public function sales(Request $request)
    {
        return view('reports.sales', $this->salesData($request));
    }

    public function salesPdf(Request $request)
    {
        $data = $this->salesData($request);
        $pdf = Pdf::loadView('pdf.reports.sales', $data + $this->pdfMeta());

        return $pdf->download('ZPOS_Sales_Report_' . $data['startDate'] . '_to_' . $data['endDate'] . '.pdf');
    }

    private function salesData(Request $request): array
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $branchId = $this->getActiveBranchId();

        // Pro-forma sales are draft quotes — not real sales until converted (markAsPaid).
        $sales = \App\Models\Sale::with('customer')
            ->where('payment_status', '!=', 'proforma')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('sale_date', 'desc')
            ->get();

        $totalRefunds = \App\Models\SaleReturn::where('shop_id', auth()->user()->shop_id)
            ->whereBetween('return_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->sum('total_refund');

        $totalSales = $sales->sum('total_amount') - $totalRefunds;

        return compact('startDate', 'endDate', 'sales', 'totalSales', 'totalRefunds');
    }

    /**
     * Per-product stock movement: opening stock, units sold, and what's left —
     * so a shop owner can see turnover (fast vs slow movers) without digging
     * into Products or a new Sale just to check quantities.
     */
    public function stockMovement(Request $request)
    {
        return view('reports.stock_movement', $this->stockMovementData($request));
    }

    public function stockMovementPdf(Request $request)
    {
        $data = $this->stockMovementData($request);
        $pdf = Pdf::loadView('pdf.reports.stock_movement', $data + $this->pdfMeta());

        return $pdf->download('ZPOS_Stock_Movement_' . now()->format('Y-m-d') . '.pdf');
    }

    private function stockMovementData(Request $request): array
    {
        $search = trim((string) $request->query('search'));
        $branchId = $this->getActiveBranchId();
        $shopId = auth()->user()->shop_id;
        $hasBranches = \App\Models\Branch::where('shop_id', $shopId)->exists();

        if ($hasBranches) {
            $currentStockMap = DB::table('branch_product')
                ->join('products', 'branch_product.product_id', '=', 'products.id')
                ->where('products.shop_id', $shopId)
                ->whereNull('products.deleted_at')
                ->when($branchId, function ($q) use ($branchId) {
                    $q->where('branch_product.branch_id', $branchId);
                })
                ->groupBy('products.id')
                ->selectRaw('products.id as product_id, SUM(branch_product.quantity) as current_stock')
                ->pluck('current_stock', 'product_id');
        } else {
            $currentStockMap = Product::where('shop_id', $shopId)->whereNull('deleted_at')->pluck('stock', 'id');
        }

        // Gross quantity sold, all-time. Pro-forma sales are draft quotes, not real sales.
        $soldMap = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('products.shop_id', $shopId)
            ->where('sales.payment_status', '!=', 'proforma')
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            })
            ->groupBy('sale_items.product_id')
            ->selectRaw('sale_items.product_id, SUM(sale_items.quantity) as sold_qty')
            ->pluck('sold_qty', 'product_id');

        $products = Product::where('shop_id', $shopId)
            ->whereNull('deleted_at')
            ->with(['category', 'unit'])
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('branches', function ($b) use ($branchId) {
                    $b->where('branch_id', $branchId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q1) use ($search) {
                    $q1->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('brand', function ($b) use ($search) {
                            $b->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->get();

        $damagedMap = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('defective_stocks')) {
            $damagedMap = DB::table('defective_stocks')
                ->where('shop_id', $shopId)
                ->where(function ($q) {
                    $q->where('status', 'defective')->orWhereNull('status');
                })
                ->when($branchId, function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->groupBy('product_id')
                ->selectRaw('product_id, SUM(quantity) as damaged_qty')
                ->pluck('damaged_qty', 'product_id');
        }

        // Defective returns (items returned defective by customers and not restocked)
        $defectiveReturnMap = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('sale_return_items')) {
            $defectiveReturnMap = DB::table('sale_return_items')
                ->join('sale_returns', 'sale_return_items.sale_return_id', '=', 'sale_returns.id')
                ->join('sale_items', 'sale_return_items.sale_item_id', '=', 'sale_items.id')
                ->where('sale_returns.shop_id', $shopId)
                ->where('sale_return_items.condition', 'defective')
                ->where('sale_return_items.repair_status', 'not_repaired')
                ->when($branchId, function ($q) use ($branchId) {
                    $q->where('sale_returns.branch_id', $branchId);
                })
                ->groupBy('sale_items.product_id')
                ->selectRaw('sale_items.product_id, SUM(sale_return_items.quantity) as ret_defective_qty')
                ->pluck('ret_defective_qty', 'sale_items.product_id');
        }

        $rows = $products->map(function ($p) use ($currentStockMap, $soldMap, $damagedMap, $defectiveReturnMap) {
            $current = (float) ($currentStockMap[$p->id] ?? 0);
            $sold = (float) ($soldMap[$p->id] ?? 0);
            $shopDamaged = (float) ($damagedMap[$p->id] ?? 0);
            $returnDamaged = (float) ($defectiveReturnMap[$p->id] ?? 0);
            $damaged = $shopDamaged + $returnDamaged;
            $opening = $current + $sold + $damaged;

            return (object) [
                'product' => $p,
                'opening' => $opening,
                'sold' => $sold,
                'damaged' => $damaged,
                'shopDamaged' => $shopDamaged,
                'returnDamaged' => $returnDamaged,
                'current' => $current,
                'sellThrough' => $opening > 0 ? round(($sold / $opening) * 100) : null,
            ];
        })->sortByDesc('sold')->values();

        $totalDamagedQty = (float) ($damagedMap->sum() + $defectiveReturnMap->sum());

        return compact('rows', 'search', 'totalDamagedQty');
    }

    public function expenses(Request $request)
    {
        return view('reports.expenses', $this->expensesData($request));
    }

    public function expensesPdf(Request $request)
    {
        $data = $this->expensesData($request);
        $pdf = Pdf::loadView('pdf.reports.expenses', $data + $this->pdfMeta());

        return $pdf->download('ZPOS_Expenses_Report_' . $data['startDate'] . '_to_' . $data['endDate'] . '.pdf');
    }

    private function expensesData(Request $request): array
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $branchId = $this->getActiveBranchId();

        $expenses = \App\Models\Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('expense_date', 'desc')
            ->get();

        $totalExpenses = $expenses->sum('amount');

        return compact('startDate', 'endDate', 'expenses', 'totalExpenses');
    }

    /**
     * Dedicated Damaged / Defective Stock Report
     */
    public function damagedStock(Request $request)
    {
        return view('reports.damaged_stock', $this->damagedStockData($request));
    }

    public function damagedStockPdf(Request $request)
    {
        $data = $this->damagedStockData($request);
        $pdf = Pdf::loadView('pdf.reports.damaged_stock', $data + $this->pdfMeta());

        return $pdf->download('ZPOS_Damaged_Stock_' . $data['startDate'] . '_to_' . $data['endDate'] . '.pdf');
    }

    private function damagedStockData(Request $request): array
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());
        $search = trim((string) $request->query('search'));
        $branchId = $this->getActiveBranchId();
        $shopId = auth()->user()->shop_id;

        // 1. Standalone write-offs from shop/warehouse (DefectiveStock)
        $shopDamagedRecords = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('defective_stocks')) {
            $shopQuery = \App\Models\DefectiveStock::with(['product.unit', 'branch', 'user'])
                ->where('shop_id', $shopId)
                ->where(function ($q) {
                    $q->where('status', 'defective')->orWhereNull('status');
                })
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->when($branchId, function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->when($search, function ($q) use ($search) {
                    $q->whereHas('product', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%")
                          ->orWhere('sku', 'like', "%{$search}%")
                          ->orWhere('model', 'like', "%{$search}%");
                    });
                })
                ->get();

            $shopDamagedRecords = $shopQuery->map(function ($rec) {
                $cost = (float) ($rec->product->cost_price ?? 0);
                return (object) [
                    'id' => 'ds_' . $rec->id,
                    'source_type' => 'shop_damaged',
                    'source_label' => 'Zilizoharibika Dukani',
                    'source_badge' => 'bg-danger',
                    'created_at' => $rec->created_at,
                    'product' => $rec->product,
                    'cost_price' => $cost,
                    'quantity' => (float) $rec->quantity,
                    'loss' => (float) ($rec->quantity * $cost),
                    'reason' => $rec->reason ?? __('Damaged in inventory'),
                    'user' => $rec->user,
                    'branch' => $rec->branch,
                ];
            });
        }

        // 2. Defective customer returns (SaleReturnItem)
        $returnRecords = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('sale_return_items')) {
            $returnQuery = \App\Models\SaleReturnItem::with(['saleReturn.user', 'saleReturn.branch', 'saleItem.product.unit', 'product.unit'])
                ->whereHas('saleReturn', function ($q) use ($shopId, $branchId, $startDate, $endDate) {
                    $q->where('shop_id', $shopId)
                      ->whereDate('return_date', '>=', $startDate)
                      ->whereDate('return_date', '<=', $endDate);
                    if ($branchId) $q->where('branch_id', $branchId);
                })
                ->where('condition', 'defective')
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->whereHas('saleItem.product', function ($p) use ($search) {
                            $p->where('name', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%")
                              ->orWhere('model', 'like', "%{$search}%");
                        })->orWhereHas('product', function ($p) use ($search) {
                            $p->where('name', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%")
                              ->orWhere('model', 'like', "%{$search}%");
                        });
                    });
                })
                ->get();

            $returnRecords = $returnQuery->map(function ($item) {
                $product = $item->product ?? ($item->saleItem->product ?? null);
                $cost = (float) ($item->saleItem->unit_cost ?? ($product->cost_price ?? 0));
                $date = $item->created_at ?? ($item->saleReturn->return_date ? \Carbon\Carbon::parse($item->saleReturn->return_date) : now());
                return (object) [
                    'id' => 'ret_' . $item->id,
                    'source_type' => 'customer_return',
                    'source_label' => 'Rejesho Mbovu',
                    'source_badge' => 'bg-warning text-dark',
                    'created_at' => $date,
                    'product' => $product,
                    'cost_price' => $cost,
                    'quantity' => (float) $item->quantity,
                    'loss' => (float) ($item->quantity * $cost),
                    'reason' => $item->reason ?? 'Rejesho Mbovu (Defective Return)',
                    'user' => optional($item->saleReturn)->user ?? (optional(optional($item->saleReturn)->sale)->user ?? null),
                    'branch' => optional($item->saleReturn)->branch ?? null,
                ];
            });
        }

        $records = $shopDamagedRecords->concat($returnRecords)->sortByDesc('created_at')->values();

        $shopDamagedQty = (float) $shopDamagedRecords->sum('quantity');
        $shopDamagedLoss = (float) $shopDamagedRecords->sum('loss');

        $returnQty = (float) $returnRecords->sum('quantity');
        $returnLoss = (float) $returnRecords->sum('loss');

        $totalQty = $shopDamagedQty + $returnQty;
        $totalLoss = $shopDamagedLoss + $returnLoss;

        return compact(
            'records', 'startDate', 'endDate', 'search',
            'totalQty', 'totalLoss',
            'shopDamagedQty', 'shopDamagedLoss',
            'returnQty', 'returnLoss'
        );
    }

    /**
     * Branding + "who/when generated this" fields shared by every report PDF.
     */
    private function pdfMeta(): array
    {
        $branchId = $this->getActiveBranchId();

        return [
            'shop' => auth()->user()->shop,
            'branch' => $branchId ? \App\Models\Branch::find($branchId) : null,
            'generatedAt' => now(),
            'generatedBy' => auth()->user(),
        ];
    }
}
