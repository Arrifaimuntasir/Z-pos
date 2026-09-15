<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
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
        $damagedStockQty = \App\Models\DefectiveStock::when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('quantity');

        $damagedStockLoss = \App\Models\DefectiveStock::query()
            ->join('products', 'defective_stocks.product_id', '=', 'products.id')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('defective_stocks.branch_id', $branchId);
            })
            ->selectRaw('SUM(defective_stocks.quantity * products.cost_price) as loss')
            ->value('loss') ?? 0;

        // Combined loss from products that never reached a customer in sellable condition.
        $totalDefectiveLoss = $defectiveLoss + $damagedStockLoss;

        return view('reports.index', compact(
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
        ));
    }

    public function profitLoss(Request $request)
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

        $grossProfit = $grossProfitGross - $returnProfit - $defectiveLoss;

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('amount');

        $netProfit = $grossProfit - $totalExpenses;

        return view('reports.profit_loss', compact('startDate', 'endDate', 'grossProfit', 'totalExpenses', 'netProfit', 'defectiveLoss'));
    }

    public function sales(Request $request)
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

        return view('reports.sales', compact('startDate', 'endDate', 'sales', 'totalSales', 'totalRefunds'));
    }

    public function expenses(Request $request)
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

        return view('reports.expenses', compact('startDate', 'endDate', 'expenses', 'totalExpenses'));
    }
}
