<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('applocale')) {
            App::setLocale(Session::get('applocale'));
        } elseif ($request->hasCookie('applocale_persist')) {
            $lang = $request->cookie('applocale_persist');
            App::setLocale($lang);
            Session::put('applocale', $lang);
        }
        return $next($request);
    }
}
