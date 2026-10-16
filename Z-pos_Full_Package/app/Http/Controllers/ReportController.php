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

        $totalProducts = DB::table('branch_product')
            ->join('products', 'branch_product.product_id', '=', 'products.id')
            ->where('products.shop_id', auth()->user()->shop_id)
            ->whereNull('products.deleted_at')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_product.branch_id', $branchId);
            })
            ->distinct('products.id')
            ->count('products.id');

        $totalStockValue = DB::table('branch_product')
            ->join('products', 'branch_product.product_id', '=', 'products.id')
            ->where('products.shop_id', auth()->user()->shop_id)
            ->whereNull('products.deleted_at')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_product.branch_id', $branchId);
            })
            ->sum(DB::raw('products.cost_price * branch_product.quantity'));

        $totalSalesValue = DB::table('branch_product')
            ->join('products', 'branch_product.product_id', '=', 'products.id')
            ->where('products.shop_id', auth()->user()->shop_id)
            ->whereNull('products.deleted_at')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_product.branch_id', $branchId);
            })
            ->sum(DB::raw('products.selling_price * branch_product.quantity'));

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
            ->where('sale_returns.shop_id', auth()->user()->shop_id)
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
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sale_returns.branch_id', $branchId);
            })
            ->selectRaw('SUM(sale_items.unit_cost * sale_return_items.quantity) as loss')
            ->value('loss') ?? 0;

        $grossProfit = $grossProfitGross - $returnProfit - $defectiveLoss;

        $potentialProfit = $totalSalesValue - $totalStockValue - $totalExpenses;

        $totalPurchases = \App\Models\Purchase::when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('total_amount');

        $totalRefunds = \App\Models\SaleReturn::where('shop_id', auth()->user()->shop_id)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('total_refund');

        // Standalone "Damaged Stock" write-offs (removed from inventory before ever being sold).
        // Records marked "good" went back into stock, so only still-defective ones count as loss.
        $damagedStockQty = \App\Models\DefectiveStock::where('status', 'defective')->when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('quantity');

        $damagedStockLoss = \App\Models\DefectiveStock::query()
            ->join('products', 'defective_stocks.product_id', '=', 'products.id')
            ->where('defective_stocks.status', 'defective')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('defective_stocks.branch_id', $branchId);
            })
            ->selectRaw('SUM(defective_stocks.quantity * products.cost_price) as loss')
            ->value('loss') ?? 0;

        // Combined loss from products that never reached a customer in sellable condition.
        $totalDefectiveLoss = $defectiveLoss + $damagedStockLoss;

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
            'totalDefectiveLoss'
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
        $damagedStockLoss = \App\Models\DefectiveStock::query()
            ->join('products', 'defective_stocks.product_id', '=', 'products.id')
            ->where('defective_stocks.status', 'defective')
            ->whereDate('defective_stocks.created_at', '>=', $startDate)
            ->whereDate('defective_stocks.created_at', '<=', $endDate)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('defective_stocks.branch_id', $branchId);
            })
            ->selectRaw('SUM(defective_stocks.quantity * products.cost_price) as loss')
            ->value('loss') ?? 0;

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

        $rows = $products->map(function ($p) use ($currentStockMap, $soldMap) {
            $current = (float) ($currentStockMap[$p->id] ?? 0);
            $sold = (float) ($soldMap[$p->id] ?? 0);
            $opening = $current + $sold;

            return (object) [
                'product' => $p,
                'opening' => $opening,
                'sold' => $sold,
                'current' => $current,
                'sellThrough' => $opening > 0 ? round(($sold / $opening) * 100) : null,
            ];
        })->sortByDesc('sold')->values();

        return compact('rows', 'search');
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
