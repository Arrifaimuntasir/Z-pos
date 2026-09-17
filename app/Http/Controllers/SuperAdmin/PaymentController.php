<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = \App\Models\Payment::with('shop')->orderBy('created_at', 'desc')->get();
        return view('superadmin.payments.index', compact('payments'));
    }

    public function approve(\App\Models\Payment $payment)
    {
        $payment->status = 'approved';
        $payment->save();

        $shop = $payment->shop;

        // Determine billing cycle from payment record, fallback to shop setting
        $billingCycle = $payment->billing_cycle ?? $shop->billing_cycle ?? 'monthly';

        // Update shop billing_cycle to match what was paid for
        $shop->billing_cycle = $billingCycle;

        // Extend valid_until based on billing cycle
        $base = ($shop->valid_until && $shop->valid_until > now())
            ? \Carbon\Carbon::parse($shop->valid_until)
            : now();

        if ($billingCycle === 'yearly') {
            $shop->valid_until = $base->addYear();
        } else {
            $shop->valid_until = $base->addMonth();
        }

        // Update package if payment has one recorded
        if ($payment->package) {
            $shop->package = $payment->package;
        }

        // Ensure shop is active
        $shop->is_active = true;
        $shop->save();

        $duration = $billingCycle === 'yearly' ? '1 year' : '1 month';
        return back()->with('success', "Payment approved. Shop subscription extended by {$duration}.");
    }

    public function reject(\App\Models\Payment $payment)
    {
        $payment->status = 'rejected';
        $payment->save();

        return back()->with('success', 'Payment rejected.');
    }

    public function destroy(\App\Models\Payment $payment)
    {
        // Delete receipt file from storage
        if ($payment->receipt_path) {
            $relativePath = str_replace('storage/', '', $payment->receipt_path);
            Storage::disk('public')->delete($relativePath);
        }

        $payment->delete();

        return back()->with('success', 'Payment record deleted successfully.');
    }
}
