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
        $totalProducts = Product::count();
        $totalStockValue = Product::sum(DB::raw('cost_price * stock'));
        $totalSalesValue = Product::sum(DB::raw('selling_price * stock'));
        $totalExpenses = Expense::sum('amount');
        
        $totalSalesReal = \App\Models\Sale::sum('total_amount');
        $grossProfit = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
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

        $grossProfit = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->selectRaw('SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        
        $netProfit = $grossProfit - $totalExpenses;

        return view('reports.profit_loss', compact('startDate', 'endDate', 'grossProfit', 'totalExpenses', 'netProfit'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $sales = \App\Models\Sale::with('customer')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->orderBy('sale_date', 'desc')
            ->get();

        $totalSales = $sales->sum('total_amount');

        return view('reports.sales', compact('startDate', 'endDate', 'sales', 'totalSales'));
    }

    public function expenses(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        $expenses = \App\Models\Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->orderBy('expense_date', 'desc')
            ->get();

        $totalExpenses = $expenses->sum('amount');

        return view('reports.expenses', compact('startDate', 'endDate', 'expenses', 'totalExpenses'));
    }
}
