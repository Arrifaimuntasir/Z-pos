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

        $totalProducts = Product::count();
        
        $totalStockValue = DB::table('branch_product')
            ->join('products', 'branch_product.product_id', '=', 'products.id')
            ->where('products.shop_id', auth()->user()->shop_id)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_product.branch_id', $branchId);
            })
            ->sum(DB::raw('products.cost_price * branch_product.quantity'));

        $totalSalesValue = DB::table('branch_product')
            ->join('products', 'branch_product.product_id', '=', 'products.id')
            ->where('products.shop_id', auth()->user()->shop_id)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_product.branch_id', $branchId);
            })
            ->sum(DB::raw('products.selling_price * branch_product.quantity'));

        $totalExpenses = Expense::when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('amount');
        
        $totalSalesReal = \App\Models\Sale::when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('total_amount');
        
        $grossProfit = \App\Models\SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            })
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        $potentialProfit = $totalSalesValue - $totalStockValue - $totalExpenses;

        return view('reports.index', compact(
            'totalProducts', 
            'totalStockValue', 
            'totalSalesValue', 
            'totalExpenses', 
            'potentialProfit',
            'totalSalesReal',
            'grossProfit'
        ));
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());
        $branchId = $this->getActiveBranchId();

        $grossProfit = \App\Models\SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('sales.branch_id', $branchId);
            })
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('amount');
        
        $netProfit = $grossProfit - $totalExpenses;

        return view('reports.profit_loss', compact('startDate', 'endDate', 'grossProfit', 'totalExpenses', 'netProfit'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $branchId = $this->getActiveBranchId();

        $sales = \App\Models\Sale::with('customer')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('sale_date', 'desc')
            ->get();

        $totalSales = $sales->sum('total_amount');

        return view('reports.sales', compact('startDate', 'endDate', 'sales', 'totalSales'));
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
