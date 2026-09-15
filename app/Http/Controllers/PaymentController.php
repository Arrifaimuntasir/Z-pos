<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function expired(Request $request)
    {
        $shop = auth()->user()->shop;
        $pendingPayment = \App\Models\Payment::where('shop_id', $shop->id)
            ->where('status', 'pending')
            ->first();

        $validPackages = ['starter', 'professional', 'enterprise'];

        $requestedPackage = $request->query('package');
        if (!in_array($requestedPackage, $validPackages)) {
            $requestedPackage = $shop->package ?? 'starter';
        }

        $requestedBilling = $request->query('billing');
        if (!in_array($requestedBilling, ['monthly', 'yearly'])) {
            $requestedBilling = $shop->billing_cycle === 'yearly' ? 'yearly' : 'monthly';
        }

        // Only treat this as a voluntary upgrade (not an expired/blocked account) when the shop is still in good standing.
        $isUpgradeRequest = $request->has('package')
            && $shop->is_active
            && (!$shop->valid_until || $shop->valid_until >= now());

        $requestedAmount = $requestedBilling === 'yearly'
            ? \App\Models\CmsSetting::yearlyPriceValue($requestedPackage)
            : \App\Models\CmsSetting::monthlyPriceValue($requestedPackage);

        return view('payments.expired', compact('shop', 'pendingPayment', 'requestedPackage', 'requestedBilling', 'requestedAmount', 'isUpgradeRequest'));
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

            $validPackages = ['starter', 'professional', 'enterprise'];
            $requestedPackage = in_array($request->input('package'), $validPackages) ? $request->input('package') : ($shop->package ?? 'starter');
            $requestedBilling = $request->input('billing');
            if (!in_array($requestedBilling, ['monthly', 'yearly'])) {
                $requestedBilling = $shop->billing_cycle === 'yearly' ? 'yearly' : 'monthly';
            }
            $amount = $requestedBilling === 'yearly'
                ? \App\Models\CmsSetting::yearlyPriceValue($requestedPackage)
                : \App\Models\CmsSetting::monthlyPriceValue($requestedPackage);

            \App\Models\Payment::create([
                'shop_id' => $shop->id,
                'receipt_path' => 'storage/' . $path,
                'amount' => $amount,
                'status' => 'pending',
            ]);
            
            // Notify super admins
            $superAdmins = \App\Models\User::role('Super Admin')->get();
            \Illuminate\Support\Facades\Notification::send($superAdmins, new \App\Notifications\PaymentReceiptUploaded($shop));
        }

        return back()->with('success', 'Receipt uploaded successfully. Please wait for admin approval.');
    }
}
