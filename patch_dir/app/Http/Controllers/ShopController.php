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
            'business_type' => 'required|string|in:Retail / General,Electronics / IT,Pharmacy / Health,Supermarket / Grocery,Restaurant / Food,Hardware / Construction,Clothing / Boutique',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'receipt_message' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'business_type.in' => __('Please select another business. Services / Consulting is coming soon.'),
        ]);

        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('home');
        }
        $shop->name = $request->name;
        $shop->business_type = $request->business_type;
        $shop->phone = $request->phone;
        $shop->address = $request->address;
        $shop->receipt_message = $request->receipt_message;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $shop->logo_path = 'storage/' . $path;
        }

        $shop->save();

        return back()->with('success', 'Shop settings updated successfully.');
    }
}
