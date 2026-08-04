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
    public function index()
    {
        // Revenue is total value of sales. Income is total amount paid.
        $totalRevenue = \App\Models\Sale::sum('total_amount');
        $totalIncome = \App\Models\Sale::sum('paid_amount');
        $outstandingInvoices = \App\Models\Sale::where('payment_status', '!=', 'paid')
            ->sum(\Illuminate\Support\Facades\DB::raw('total_amount - paid_amount'));

        // Total Expense is the sum of general expenses and stock purchases
        $generalExpenses = \App\Models\Expense::sum('amount');
        $purchaseExpenses = \App\Models\Purchase::sum('total_amount');
        $totalExpense = $generalExpenses + $purchaseExpenses;

        // Generate Monthly Data for Charts
        $monthlyIncome = array_fill(0, 12, 0); 
        $monthlyExpense = array_fill(0, 12, 0);
        $monthlyNetCash = array_fill(0, 12, 0);

        $currentYear = date('Y');

        // Fetch income (paid amount of sales) grouped by month
        $incomeByMonth = \App\Models\Sale::whereYear('sale_date', $currentYear)
            ->selectRaw('MONTH(sale_date) as month, SUM(paid_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        // Fetch general expenses grouped by month
        $expensesByMonth = \App\Models\Expense::whereYear('expense_date', $currentYear)
            ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();
            
        // Fetch purchases grouped by month
        $purchasesByMonth = \App\Models\Purchase::whereYear('purchase_date', $currentYear)
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

        return view('home', compact(
            'totalRevenue',
            'totalIncome',
            'totalExpense',
            'outstandingInvoices',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyNetCash'
        ));
    }
}
