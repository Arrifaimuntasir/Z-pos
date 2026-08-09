<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = \App\Models\Shop::with('users')->get();
        return view('superadmin.shops.index', compact('shops'));
    }

    public function edit(\App\Models\Shop $shop)
    {
        return view('superadmin.shops.edit', compact('shop'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Shop $shop)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'valid_until' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $shop->name = $request->name;
        $shop->valid_until = $request->valid_until;
        $shop->is_active = $request->has('is_active');
        $shop->save();

        return redirect()->route('superadmin.shops.index')->with('success', 'Shop updated successfully.');
    }

    public function toggleStatus(\App\Models\Shop $shop)
    {
        $shop->is_active = !$shop->is_active;
        $shop->save();

        $status = $shop->is_active ? 'activated' : 'suspended';
        return back()->with('success', "Shop has been successfully {$status}.");
    }
}
