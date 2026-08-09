<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/features', function () {
    return view('pages.features');
});

Route::get('/pricing', function () {
    return view('pages.pricing');
});

Route::get('/testimonials', function () {
    return view('pages.testimonials');
});

Route::get('/about', function () {
    return view('pages.about');
});

Route::get('/contact', function () {
    return view('pages.contact');
});

Route::get('/privacy', function () {
    return view('pages.privacy');
});

Route::get('/terms', function () {
    return view('pages.terms');
});

Route::get('/cookies', function () {
    return view('pages.cookies');
});

Auth::routes();

// Google Auth Routes
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {
    // Payment routes for expired/suspended shops
    Route::get('/payments/expired', [App\Http\Controllers\PaymentController::class, 'expired'])->name('payments.expired');
    Route::post('/payments/upload', [App\Http\Controllers\PaymentController::class, 'upload'])->name('payments.upload');
    Route::get('/shop/suspended', [App\Http\Controllers\PaymentController::class, 'suspended'])->name('shop.suspended');

    // Routes that require an active subscription
    Route::middleware(['subscription'])->group(function () {
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Cashier & Admin shared routes
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::resource('sales', App\Http\Controllers\SaleController::class);
    
    // Admin Only Routes
    // Shop Settings
    Route::get('/shop/settings', [App\Http\Controllers\ShopController::class, 'edit'])->name('shop.settings');
    Route::put('/shop/settings', [App\Http\Controllers\ShopController::class, 'update'])->name('shop.settings.update');
    
    // Master Data & Inventory
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', App\Http\Controllers\BrandController::class);
    Route::resource('units', App\Http\Controllers\UnitController::class);
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);
    
    // Expenses & Reports
    Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/profit-loss', [App\Http\Controllers\ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/expenses', [App\Http\Controllers\ReportController::class, 'expenses'])->name('reports.expenses');
    
    // Staff Management
    Route::resource('staff', App\Http\Controllers\StaffController::class)->except(['show', 'edit', 'update']);
    
    // Maintenance Toggle
    Route::post('/maintenance/toggle', function (\Illuminate\Http\Request $request) {
        if (!auth()->user()->hasRole('Super Admin') && !auth()->user()->hasRole('Administrator')) {
            abort(403);
        }
        $isMaintenance = \Illuminate\Support\Facades\Cache::get('site_maintenance', false);
        \Illuminate\Support\Facades\Cache::put('site_maintenance', !$isMaintenance);
        return response()->json(['status' => !$isMaintenance]);
    })->name('maintenance.toggle');
    
    }); // End of subscription middleware

    // Super Admin Routes
    Route::middleware(['superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/shops', [App\Http\Controllers\SuperAdmin\ShopController::class, 'index'])->name('shops.index');
        Route::get('/shops/{shop}/edit', [App\Http\Controllers\SuperAdmin\ShopController::class, 'edit'])->name('shops.edit');
        Route::put('/shops/{shop}', [App\Http\Controllers\SuperAdmin\ShopController::class, 'update'])->name('shops.update');
        Route::post('/shops/{shop}/toggle-status', [App\Http\Controllers\SuperAdmin\ShopController::class, 'toggleStatus'])->name('shops.toggle-status');
        
        Route::get('/payments', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/approve', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'reject'])->name('payments.reject');
    });

});
