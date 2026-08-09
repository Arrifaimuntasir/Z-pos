<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CustomMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Cache::get('site_maintenance') === true) {
            // Allow admin/superadmin to bypass
            if (Auth::check() && (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Administrator'))) {
                return $next($request);
            }
            
            // Allow toggle route to bypass
            if ($request->is('maintenance/toggle') || $request->is('logout')) {
                return $next($request);
            }

            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
