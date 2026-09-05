<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;



use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/onboarding', function () {
    return view('pwa.onboarding');
})->name('pwa.onboarding');

Route::get('/safi', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return "<h2 style='color:green; text-align:center; margin-top:50px;'>Kila kitu kimesafishwa vizuri (Cache Cleared)!</h2><p style='text-align:center;'><a href='/home'>Rudi Kwenye Mfumo</a></p>";
});

Route::get('/fix_user_id', function () {
    try {
        $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn('sales', 'user_id');
        if (!$hasColumn) {
            \Illuminate\Support\Facades\Schema::table('sales', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('shop_id');
            });
            \Illuminate\Support\Facades\DB::table('migrations')->insertOrIgnore([
                'migration' => '2026_09_05_023413_add_user_id_to_sales_table',
                'batch' => \Illuminate\Support\Facades\DB::table('migrations')->max('batch') + 1,
            ]);
            return "<h2 style='color:green; text-align:center; margin-top:50px;'>✅ Kolamu ya user_id imeongezwa kwenye mauzo (sales) kikamilifu!</h2><p style='text-align:center;'><a href='/home'>Rudi Kwenye Mfumo</a></p>";
        } else {
            return "<h2 style='color:blue; text-align:center; margin-top:50px;'>ℹ️ Kolamu ya user_id tayari ipo - hakuna kitu cha kufanya.</h2><p style='text-align:center;'><a href='/home'>Rudi Kwenye Mfumo</a></p>";
        }
    } catch (\Exception $e) {
        return "<h2 style='color:red; text-align:center; margin-top:50px;'>❌ Kuna hitilafu: " . $e->getMessage() . "</h2>";
    }
});

Route::get('/debug_sales', function () {
    if (!auth()->check()) return redirect('/login');
    $user = auth()->user();
    $roles = $user->getRoleNames()->implode(', ');
    $isAdmin = $user->hasRole('Administrator');
    $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn('sales', 'user_id');
    $totalSales = \App\Models\Sale::count();
    $mySales = $hasColumn ? \App\Models\Sale::where('user_id', $user->id)->count() : 'N/A';
    $nullSales = $hasColumn ? \App\Models\Sale::whereNull('user_id')->count() : 'N/A';

    // Get last 5 sales with user_id to see what's stored
    $lastSales = $hasColumn
        ? \Illuminate\Support\Facades\DB::table('sales')
            ->select('id','reference_no','user_id','created_at')
            ->where('shop_id', $user->shop_id)
            ->orderBy('created_at','desc')
            ->limit(5)
            ->get()
        : collect();

    $salesRows = '';
    foreach ($lastSales as $s) {
        $salesRows .= "  {$s->id} | {$s->reference_no} | user_id=" . ($s->user_id ?? 'NULL') . " | {$s->created_at}\n";
    }

    // Check if filter code exists in the controller file
    $controllerPath = app_path('Http/Controllers/SaleController.php');
    $controllerContent = file_exists($controllerPath) ? file_get_contents($controllerPath) : '';
    $hasFilterCode = str_contains($controllerContent, 'isAdmin') && str_contains($controllerContent, 'user_id');
    $hasUserIdStore = str_contains($controllerContent, "'user_id' => auth()->id()");

    return "<pre style='font-family:monospace; font-size:15px; margin:40px;'>
=== DEBUG: Sales Filter Check ===

Logged-in User ID:   {$user->id}
User Name:           {$user->name}
Role(s):             {$roles}
isAdmin check:       " . ($isAdmin ? 'TRUE (admin - sees all)' : 'FALSE (staff - filter applied)') . "

--- Database ---
user_id column exists:       " . ($hasColumn ? 'YES ✅' : 'NO ❌ - Visit /fix_user_id') . "
Total sales in shop:         {$totalSales}
My sales (user_id={$user->id}): {$mySales}
Sales with NULL user_id:     {$nullSales}

--- Last 5 Sales (newest first) ---
{$salesRows}
--- Controller File Check ---
Filter code exists (isAdmin + user_id filter): " . ($hasFilterCode ? 'YES ✅' : 'NO ❌ - OLD Controller on server!') . "
Stores user_id on sale create:                 " . ($hasUserIdStore ? 'YES ✅' : 'NO ❌ - OLD Controller on server!') . "

--- Conclusion ---
" . (!$hasColumn
    ? '❌ PROBLEM: user_id column missing. Visit /fix_user_id first.'
    : (!$hasFilterCode
        ? '❌ PROBLEM: SaleController.php on server is OLD (no filter code). Must re-upload the controller file!'
        : (!$isAdmin
            ? "✅ Everything OK. Staff sees only their {$mySales} sales."
            : "ℹ️ Admin mode. All {$totalSales} sales visible."))) . "
</pre>";
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
Route::get('/registration-success', [\App\Http\Controllers\Auth\RegisterController::class, 'success'])->name('registration.success');

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
    Route::get('/sales/{sale}/returns/create', [App\Http\Controllers\SaleReturnController::class, 'create'])->name('sales.returns.create');
    Route::post('/sales/{sale}/returns', [App\Http\Controllers\SaleReturnController::class, 'store'])->name('sales.returns.store');
    Route::get('/returns', [App\Http\Controllers\SaleReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/defective', [App\Http\Controllers\SaleReturnController::class, 'defectiveItems'])->name('returns.defective');
    Route::get('/returns/{return}/pdf', [App\Http\Controllers\SaleReturnController::class, 'downloadPdf'])->name('returns.pdf');
    Route::post('/sales/bulk-destroy', [App\Http\Controllers\SaleController::class, 'bulkDestroy'])->name('sales.bulk-destroy');
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
    // Profile
    // Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    // Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    // Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/destroy', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
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
    Route::resource('branches', \App\Http\Controllers\BranchController::class)->except(['show']);
    Route::post('/switch-branch', [\App\Http\Controllers\BranchSwitchController::class, 'switch'])->name('branch.switch');
    
    // Staff Management
    Route::resource('staff', App\Http\Controllers\StaffController::class)->except(['show']);
    
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
