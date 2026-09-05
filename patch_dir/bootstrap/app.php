<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        $middleware->append(\App\Http\Middleware\CustomMaintenance::class);
        $middleware->alias([
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'subscription' => \App\Http\Middleware\SubscriptionMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            // Kama ni API request au AJAX (kama push-subscription fetch), rudisha kosa la JSON
            if ($request->expectsJson() || $request->isXmlHttpRequest() || str_contains($request->header('Accept', ''), 'application/json') || $request->is('push-subscriptions*')) {
                return response()->json(['message' => 'CSRF token mismatch.'], 419);
            }
            // Kama ni ukurasa wa kawaida, rudisha nyuma na kutoa ujumbe
            return redirect()->back()->withInput($request->except('_token'))->with('error', 'Kipindi cha usalama kimeisha (Session Expired). Tafadhali jaribu tena.');
        });
    })->create();
