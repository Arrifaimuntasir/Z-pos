<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->hasRole('Super Admin')) {
            $shop = auth()->user()->shop;
            
            // If shop is completely inactive/blocked
            if (!$shop->is_active) {
                return redirect()->route('shop.suspended');
            }
            
            // If subscription has expired
            if ($shop->valid_until && $shop->valid_until < now()) {
                // Avoid infinite redirect loop if already on the payment page
                if (!$request->routeIs('payments.*')) {
                    return redirect()->route('payments.expired');
                }
            }
        }

        return $next($request);
    }
}
