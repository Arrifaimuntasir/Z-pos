<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function expired()
    {
        $shop = auth()->user()->shop;
        $pendingPayment = \App\Models\Payment::where('shop_id', $shop->id)
            ->where('status', 'pending')
            ->first();

        return view('payments.expired', compact('shop', 'pendingPayment'));
    }

    public function suspended()
    {
        $shop = auth()->user()->shop;
        $pendingPayment = \App\Models\Payment::where('shop_id', $shop->id)
            ->where('status', 'pending')
            ->first();

        return view('payments.suspended', compact('shop', 'pendingPayment'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $shop = auth()->user()->shop;

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');

            \App\Models\Payment::create([
                'shop_id' => $shop->id,
                'receipt_path' => 'storage/' . $path,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Receipt uploaded successfully. Please wait for admin approval.');
    }
}
