<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $branchId = $this->getActiveBranchId();
        
        $query = Expense::when($search, function ($q) use ($search) {
            $q->where(function ($q1) use ($search) {
                $q1->where('description', 'like', "%{$search}%")
                   ->orWhere('category', 'like', "%{$search}%");
            });
        })->when($branchId, function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        });

        $totalExpenses = $query->sum('amount');
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15)->appends(['search' => $search]);
        
        return view('expenses.index', compact('expenses', 'search', 'totalExpenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        $branchId = $request->branch_id ?? $this->getActiveBranchId();
        if (!$branchId) {
            $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
        }
        $validated['branch_id'] = $branchId;

        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
