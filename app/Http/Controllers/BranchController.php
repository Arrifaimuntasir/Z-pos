<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $shopId = Auth::user()->shop_id;
        $branches = Branch::where('shop_id', $shopId)->get();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::find($shopId);
        $currentBranches = Branch::where('shop_id', $shopId)->count();
        
        $package = $shop->package ?? 'starter';
        $branchLimit = 1;
        if ($package === 'professional') $branchLimit = 5;
        if ($package === 'enterprise') $branchLimit = 9999;
        
        if ($currentBranches >= $branchLimit) {
            return redirect()->route('branches.index')->with('error', "You have reached the maximum number of branches for your current {$package} plan ({$branchLimit} Branches limit).");
        }

        return view('branches.create');
    }

    public function store(Request $request)
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::find($shopId);
        $currentBranches = Branch::where('shop_id', $shopId)->count();
        
        $package = $shop->package ?? 'starter';
        $branchLimit = 1;
        if ($package === 'professional') $branchLimit = 5;
        if ($package === 'enterprise') $branchLimit = 9999;
        
        if ($currentBranches >= $branchLimit) {
            return redirect()->route('branches.index')->with('error', 'Branch limit reached.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['shop_id'] = $shopId;
        $validated['is_active'] = true;

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        if ($branch->shop_id !== Auth::user()->shop_id) {
            abort(403);
        }
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        if ($branch->shop_id !== Auth::user()->shop_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }
}
