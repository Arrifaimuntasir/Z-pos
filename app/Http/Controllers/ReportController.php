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
        
        // Very basic profit estimate based on current stock value difference minus expenses
        $potentialProfit = $totalSalesValue - $totalStockValue - $totalExpenses;

        return view('reports.index', compact(
            'totalProducts', 
            'totalStockValue', 
            'totalSalesValue', 
            'totalExpenses', 
            'potentialProfit'
        ));
    }
}
