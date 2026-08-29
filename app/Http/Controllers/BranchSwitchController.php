<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchSwitchController extends Controller
{
    /**
     * Switch the active branch for the current session.
     */
    public function switch(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (empty($branchId) || $branchId === 'all') {
            $request->session()->forget('active_branch_id');
            return back()->with('success', 'Now viewing All Branches');
        }

        // Verify the branch exists and belongs to the user's shop
        $branch = Branch::where('shop_id', auth()->user()->shop_id)
            ->where('id', $branchId)
            ->first();

        if ($branch) {
            $request->session()->put('active_branch_id', $branch->id);
            return back()->with('success', 'Switched to ' . $branch->name);
        }

        return back()->with('error', 'Branch not found or unauthorized.');
    }
}
