<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $shopId = Auth::user()->shop_id;
        $staff = User::where('shop_id', $shopId)->where('id', '!=', Auth::id())->with('branch')->get();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $shopId = Auth::user()->shop_id;
        $shop = \App\Models\Shop::find($shopId);
        $currentUsers = User::where('shop_id', $shopId)->count();
        
        $package = $shop->package ?? 'starter';
        $userLimit = ($package === 'starter') ? 2 : 9999;
        
        if ($currentUsers >= $userLimit) {
            return redirect()->route('staff.index')->with('error', "You have reached the maximum number of users for your current {$package} plan ({$userLimit} Users limit). Upgrade to add more staff.");
        }

        $branches = \App\Models\Branch::where('shop_id', $shopId)->get();

        return view('staff.create', compact('shop', 'userLimit', 'branches'));
    }

    public function store(Request $request)
    {
        $shopId = Auth::user()->shop_id;
        $shop = \App\Models\Shop::find($shopId);
        $currentUsers = User::where('shop_id', $shopId)->count();
        
        $package = $shop->package ?? 'starter';
        $userLimit = ($package === 'starter') ? 2 : 9999;
        
        if ($currentUsers >= $userLimit) {
            return redirect()->route('staff.index')->with('error', "You have reached the maximum number of users ({$userLimit} Users limit).");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Ensure the branch belongs to the shop
        $branch = \App\Models\Branch::find($validated['branch_id']);
        if ($branch->shop_id !== $shopId) {
            abort(403);
        }

        $nameParts = explode(' ', $validated['name']);
        $firstName = array_shift($nameParts);
        $lastName = count($nameParts) > 0 ? implode(' ', $nameParts) : '';

        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'shop_id' => $shopId,
            'branch_id' => $validated['branch_id'],
        ]);

        $user->assignRole('Cashier');

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->shop_id !== Auth::user()->shop_id || $staff->id === Auth::id()) {
            abort(403);
        }
        
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed successfully.');
    }
}
