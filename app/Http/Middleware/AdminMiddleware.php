<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Super Admin'))) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        abort(403, 'You do not have permission to access this page. Only Administrators can view this content.');
    }
}
