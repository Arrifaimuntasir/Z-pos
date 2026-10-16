<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        
        // Add 1 month to valid_until, or from today if already expired
        if ($shop->valid_until && $shop->valid_until > now()) {
            $shop->valid_until = \Carbon\Carbon::parse($shop->valid_until)->addMonth();
        } else {
            $shop->valid_until = now()->addMonth();
        }
        
        // Ensure shop is active
        $shop->is_active = true;
        $shop->save();

        return back()->with('success', 'Payment approved. Shop subscription extended by 1 month.');
    }

    public function reject(\App\Models\Payment $payment)
    {
        $payment->status = 'rejected';
        $payment->save();

        return back()->with('success', 'Payment rejected.');
    }
}
