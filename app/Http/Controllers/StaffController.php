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
        $staff = User::where('shop_id', $shopId)->where('id', '!=', Auth::id())->get();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $shopId = Auth::user()->shop_id;
        $currentUsers = User::where('shop_id', $shopId)->count();
        
        // Since Starter is 2 users limit (including owner), check limit
        if ($currentUsers >= 2) {
            // For now, hardcode the limit to 2 as per Starter plan for testing without a subscription manager.
            // If we had a subscription model, we would check the active plan.
            return redirect()->route('staff.index')->with('error', 'You have reached the maximum number of users for your current plan (2 Users limit). Upgrade to add more staff.');
        }

        return view('staff.create');
    }

    public function store(Request $request)
    {
        $shopId = Auth::user()->shop_id;
        $currentUsers = User::where('shop_id', $shopId)->count();
        
        if ($currentUsers >= 2) {
            return redirect()->route('staff.index')->with('error', 'You have reached the maximum number of users (2 Users limit).');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $nameParts = explode(' ', $validated['name']);
        $firstName = array_shift($nameParts);
        $lastName = count($nameParts) > 0 ? implode(' ', $nameParts) : '';

        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'shop_id' => $shopId,
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
