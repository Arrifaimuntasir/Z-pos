<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function edit()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('home');
        }
        return view('shop.settings', compact('shop'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('home');
        }
        $shop->name = $request->name;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $shop->logo_path = 'storage/' . $path;
        }

        $shop->save();

        return back()->with('success', 'Shop settings updated successfully.');
    }
}
