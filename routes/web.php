<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;



use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/safi', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return "<h2 style='color:green; text-align:center; margin-top:50px;'>Kila kitu kimesafishwa vizuri (Cache Cleared)!</h2><p style='text-align:center;'><a href='/home'>Rudi Kwenye Mfumo</a></p>";
});

Route::get('/fix_sku', function () {
    try {
        \Illuminate\Support\Facades\Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
            $indexes = \Illuminate\Support\Facades\Schema::getIndexes('products');
            $hasGlobalSku = false; $hasGlobalBarcode = false; $hasShopSku = false; $hasShopBarcode = false;
            foreach ($indexes as $index) {
                if ($index['name'] === 'products_sku_unique' || ($index['unique'] && count($index['columns']) == 1 && $index['columns'][0] === 'sku')) $hasGlobalSku = $index['name'];
                if ($index['name'] === 'products_barcode_unique' || ($index['unique'] && count($index['columns']) == 1 && $index['columns'][0] === 'barcode')) $hasGlobalBarcode = $index['name'];
                if ($index['unique'] && count($index['columns']) == 2) {
                    if (in_array('shop_id', $index['columns']) && in_array('sku', $index['columns'])) $hasShopSku = true;
                    if (in_array('shop_id', $index['columns']) && in_array('barcode', $index['columns'])) $hasShopBarcode = true;
                }
            }
            if ($hasGlobalSku) $table->dropUnique($hasGlobalSku);
            if ($hasGlobalBarcode) $table->dropUnique($hasGlobalBarcode);
            if (!$hasShopSku) $table->unique(['shop_id', 'sku']);
            if (!$hasShopBarcode) $table->unique(['shop_id', 'barcode']);
        });
        return "<h2 style='color:green; text-align:center; margin-top:50px;'>Tatizo la SKU na Barcode limesuluhishwa kikamilifu! (Database Fixed)</h2><p style='text-align:center;'><a href='/home'>Rudi Kwenye Mfumo</a></p>";
    } catch (\Exception $e) {
        return "<h2 style='color:red; text-align:center; margin-top:50px;'>Kuna shida kidogo: " . $e->getMessage() . "</h2>";
    }
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
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.submit');

Route::get('/privacy', function () {
    return view('pages.privacy');
});

Route::get('/terms', function () {
    return view('pages.terms');
});

Route::get('/cookies', function () {
    return view('pages.cookies');
});

Auth::routes(['verify' => true]);

// Google Auth Routes
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['auth', 'verified'])->group(function () {
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
    Route::post('/sales/{sale}/mark-paid', [App\Http\Controllers\SaleController::class, 'markAsPaid'])->name('sales.mark_paid');
    Route::get('/sales/{sale}/pdf', [App\Http\Controllers\SaleController::class, 'downloadPdf'])->name('sales.pdf');
    Route::resource('invoices', App\Http\Controllers\InvoiceController::class)->only(['index', 'show']);
    Route::get('/invoices/{invoice}/pdf', [App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::resource('warranties', App\Http\Controllers\WarrantyController::class);
    Route::get('/warranties/{warranty}/show', [App\Http\Controllers\WarrantyController::class, 'show'])->name('warranties.show');
    Route::get('/warranties/{warranty}/pdf', [App\Http\Controllers\WarrantyController::class, 'downloadPdf'])->name('warranties.pdf');
    
    // Admin Only Routes
    Route::middleware(['admin'])->group(function () {
    // Shop Settings
    Route::get('/shop/settings', [App\Http\Controllers\ShopController::class, 'edit'])->name('shop.settings');
    Route::put('/shop/settings', [App\Http\Controllers\ShopController::class, 'update'])->name('shop.settings.update');
    Route::get('/shop/business-card', [App\Http\Controllers\BusinessCardController::class, 'index'])->name('shop.business-card');
    Route::post('/shop/business-card/save', [App\Http\Controllers\BusinessCardController::class, 'save'])->name('shop.business-card.save');

    // Push Notifications
    Route::post('/push-subscriptions', [App\Http\Controllers\PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [App\Http\Controllers\PushSubscriptionController::class, 'destroy']);
    
    // Master Data & Inventory
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', App\Http\Controllers\BrandController::class);
    Route::resource('units', App\Http\Controllers\UnitController::class);
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
    Route::resource('products', ProductController::class);
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);
    
    // Expenses & Reports
    Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/profit-loss', [App\Http\Controllers\ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/expenses', [App\Http\Controllers\ReportController::class, 'expenses'])->name('reports.expenses');
    
    // Branches
    Route::resource('branches', \App\Http\Controllers\BranchController::class)->except(['show', 'destroy']);
    Route::post('/switch-branch', [\App\Http\Controllers\BranchSwitchController::class, 'switch'])->name('branch.switch');
    
    // Staff Management
    Route::resource('staff', App\Http\Controllers\StaffController::class)->except(['show', 'edit', 'update']);
    
    // Maintenance Toggle
    Route::post('/maintenance/toggle', function (\Illuminate\Http\Request $request) {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
        $isMaintenance = \Illuminate\Support\Facades\Cache::get('site_maintenance', false);
        \Illuminate\Support\Facades\Cache::put('site_maintenance', !$isMaintenance);
        return response()->json(['status' => !$isMaintenance]);
    })->name('maintenance.toggle');
    
    }); // End of Admin Only Routes
    
    }); // End of subscription middleware

    // Super Admin Routes
    Route::middleware(['superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/shops', [App\Http\Controllers\SuperAdmin\ShopController::class, 'index'])->name('shops.index');
        Route::get('/shops/{shop}/edit', [App\Http\Controllers\SuperAdmin\ShopController::class, 'edit'])->name('shops.edit');
        Route::put('/shops/{shop}', [App\Http\Controllers\SuperAdmin\ShopController::class, 'update'])->name('shops.update');
        Route::post('/shops/{shop}/toggle-status', [App\Http\Controllers\SuperAdmin\ShopController::class, 'toggleStatus'])->name('shops.toggle-status');
        Route::delete('/shops/{shop}', [App\Http\Controllers\SuperAdmin\ShopController::class, 'destroy'])->name('shops.destroy');
        
        Route::get('/payments', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/approve', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'reject'])->name('payments.reject');
    });

});

Route::get('lang/{lang}', [\App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

Route::get('/db-hotfix', function () {
    foreach(\App\Models\Shop::all() as $shop) { 
        $branch = \App\Models\Branch::where('shop_id', $shop->id)->first(); 
        if($branch) { 
            \App\Models\Sale::where('shop_id', $shop->id)->whereNull('branch_id')->update(['branch_id' => $branch->id]); 
            \App\Models\Expense::where('shop_id', $shop->id)->whereNull('branch_id')->update(['branch_id' => $branch->id]); 
            \App\Models\Purchase::where('shop_id', $shop->id)->whereNull('branch_id')->update(['branch_id' => $branch->id]); 
        } 
    }
    return "HOTFIX SUCCESS. DATA RECOVERED.";
});

Route::get('/system-update', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "Mfumo umesafishwa (Cache cleared) na Database imesasishwa (Migrated) kikamilifu!";
    } catch (\Exception $e) {
        return "Imeshindikana: " . $e->getMessage();
    }
});
