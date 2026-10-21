<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;



use App\Http\Controllers\ProductController;


use App\Models\Testimonial;

Route::get('/', function () {
    $dbTestimonials = Testimonial::active()
        ->orderBy('sort_order')
        ->orderByDesc('created_at')
        ->get();
    return view('welcome', compact('dbTestimonials'));
});

Route::get('/onboarding', function () {
    return view('pwa.onboarding');
})->name('pwa.onboarding');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/safi', function () {
        $deletedViews = 0;
        $viewDir = storage_path('framework/views');
        if (is_dir($viewDir)) {
            foreach (glob($viewDir . '/*.php') as $f) {
                if (@unlink($f)) $deletedViews++;
            }
        }

        try { \Illuminate\Support\Facades\Artisan::call('view:clear'); } catch (\Throwable $e) {}
        try { \Illuminate\Support\Facades\Artisan::call('cache:clear'); } catch (\Throwable $e) {}
        try { \Illuminate\Support\Facades\Artisan::call('config:clear'); } catch (\Throwable $e) {}
        try { \Illuminate\Support\Facades\Artisan::call('route:clear'); } catch (\Throwable $e) {}

        $opcacheStatus = 'Haipatikani';
        if (function_exists('opcache_reset')) {
            $opcacheStatus = @opcache_reset() ? 'Imesafishwa Kikamilifu' : 'Hitilafu ya OPcache';
        }

        try {
            \App\Models\StockMovement::ensureColumnTypes();
        } catch (\Throwable $e) {}

        return "
        <!DOCTYPE html>
        <html lang='sw'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>Z-POS System Cache Cleared</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap' rel='stylesheet'>
            <style>
                body { font-family: 'Poppins', sans-serif; background: #0b1120; color: #ffffff; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; }
                .box { background: #111827; border: 1.5px solid #1f2937; border-radius: 16px; padding: 36px 30px; max-width: 520px; width: 100%; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
                .icon { font-size: 3rem; margin-bottom: 16px; }
                h2 { color: #34d399; margin: 0 0 12px 0; font-size: 1.45rem; }
                p { color: #e2e8f0; font-size: 0.92rem; line-height: 1.6; margin: 0 0 20px 0; }
                .badge-stat { background: #1f2937; color: #60a5fa; padding: 6px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; display: inline-block; margin-bottom: 24px; border: 1px solid #374151; }
                .btn { display: inline-block; background: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; transition: background 0.2s ease; }
                .btn:hover { background: #1d4ed8; }
            </style>
        </head>
        <body>
            <div class='box'>
                <div class='icon'>✨</div>
                <h2>Kila Kitu Kimesafishwa Vizuri!</h2>
                <p>Cache zote za mfumo (Blade views zilizokuwa zikizuia mabadiliko, OPcache ya PHP, na kumbukumbu ya database) zimefutwa kikamilifu.</p>
                <div class='badge-stat'>Files za View zilizofutwa: $deletedViews | OPcache: $opcacheStatus</div>
                <div>
                    <a href='/home' class='btn'>Rudi Kwenye Dashboard &rarr;</a>
                </div>
            </div>
        </body>
        </html>
        ";
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
        $user = auth()->user();
        $roles = $user->getRoleNames()->implode(', ');
        $isAdmin = $user->hasRole('Administrator');
        $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn('sales', 'user_id');
        $totalSales = \App\Models\Sale::count();
        $mySales = $hasColumn ? \App\Models\Sale::where('user_id', $user->id)->count() : 'N/A';
        $nullSales = $hasColumn ? \App\Models\Sale::whereNull('user_id')->count() : 'N/A';

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

// Language Switcher Route
Route::get('/lang/{lang}', [App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

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

    // Petroleum Station: attendants open the day and record fuel sales
    Route::prefix('petroleum')->name('petroleum.')->group(function () {
        Route::get('/day', [App\Http\Controllers\Petroleum\FuelDayController::class, 'index'])->name('day.index');
        Route::post('/day/open', [App\Http\Controllers\Petroleum\FuelDayController::class, 'open'])->name('day.open');
        Route::get('/sales', [App\Http\Controllers\Petroleum\FuelSaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [App\Http\Controllers\Petroleum\FuelSaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [App\Http\Controllers\Petroleum\FuelSaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{sale}', [App\Http\Controllers\Petroleum\FuelSaleController::class, 'show'])->name('sales.show');
    });
    // Products management (mutating actions authorized via canManageProducts())
    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
    Route::resource('products', ProductController::class);
    Route::delete('/customers/bulk-destroy', [App\Http\Controllers\CustomerController::class, 'bulkDestroy'])->name('customers.bulk-destroy');
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::get('/sales/{sale}/returns/create', [App\Http\Controllers\SaleReturnController::class, 'create'])->name('sales.returns.create');
    Route::post('/sales/{sale}/returns', [App\Http\Controllers\SaleReturnController::class, 'store'])->name('sales.returns.store');
    Route::get('/returns', [App\Http\Controllers\SaleReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/defective', [App\Http\Controllers\SaleReturnController::class, 'defectiveItems'])->name('returns.defective');
    Route::put('/returns/defective/{item}/repair', [App\Http\Controllers\SaleReturnController::class, 'updateRepairStatus'])->name('returns.defective.repair-status');
    Route::delete('/returns/defective/{item}', [App\Http\Controllers\SaleReturnController::class, 'destroyDefectiveItem'])->name('returns.defective.destroy');
    Route::get('/returns/{return}/pdf', [App\Http\Controllers\SaleReturnController::class, 'downloadPdf'])->name('returns.pdf');
    Route::post('/sales/bulk-destroy', [App\Http\Controllers\SaleController::class, 'bulkDestroy'])->name('sales.bulk-destroy');
    Route::resource('sales', App\Http\Controllers\SaleController::class);
    Route::post('/sales/{sale}/mark-paid', [App\Http\Controllers\SaleController::class, 'markAsPaid'])->name('sales.mark_paid');
    Route::get('/sales/{sale}/pdf', [App\Http\Controllers\SaleController::class, 'downloadPdf'])->name('sales.pdf');
    Route::resource('invoices', App\Http\Controllers\InvoiceController::class)->only(['index', 'show']);
    Route::get('/invoices/{invoice}/pdf', [App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::delete('/warranties/bulk-destroy', [App\Http\Controllers\WarrantyController::class, 'bulkDestroy'])->name('warranties.bulk-destroy');
    Route::resource('warranties', App\Http\Controllers\WarrantyController::class);
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
    Route::post('/shop/theme', [App\Http\Controllers\ShopController::class, 'updateTheme'])->name('shop.theme');
    Route::get('/shop/business-card', [App\Http\Controllers\BusinessCardController::class, 'index'])->name('shop.business-card');
    Route::post('/shop/business-card/save', [App\Http\Controllers\BusinessCardController::class, 'save'])->name('shop.business-card.save');

    // Subscription Plans
    Route::get('/plans', [App\Http\Controllers\PlansController::class, 'index'])->name('plans.index');
    
    // Rate Us / Reviews
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Push Notifications
    Route::post('/push-subscriptions', [App\Http\Controllers\PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [App\Http\Controllers\PushSubscriptionController::class, 'destroy']);
    
    // Master Data & Inventory
    // Warehouse Management (Admin Only)
    Route::get('/warehouses/product-stock', [App\Http\Controllers\WarehouseController::class, 'getProductStock'])->name('warehouses.product-stock');
    Route::post('/warehouses/{warehouse}/add-stock', [App\Http\Controllers\WarehouseController::class, 'addStockDirect'])->name('warehouses.add-stock');
    Route::resource('warehouses', App\Http\Controllers\WarehouseController::class);
    Route::get('/stock-transfer', [App\Http\Controllers\WarehouseController::class, 'transferCreate'])->name('stock.transfer.create');
    Route::post('/stock-transfer', [App\Http\Controllers\WarehouseController::class, 'transferStore'])->name('stock.transfer.store');
    Route::get('/stock-movements', [App\Http\Controllers\WarehouseController::class, 'movements'])->name('stock.movements.index');

    Route::delete('/categories/bulk-destroy', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy');
    Route::resource('categories', CategoryController::class);
    Route::delete('/brands/bulk-destroy', [App\Http\Controllers\BrandController::class, 'bulkDestroy'])->name('brands.bulk-destroy');
    Route::resource('brands', App\Http\Controllers\BrandController::class);
    Route::delete('/units/bulk-destroy', [App\Http\Controllers\UnitController::class, 'bulkDestroy'])->name('units.bulk-destroy');
    Route::resource('units', App\Http\Controllers\UnitController::class);
    Route::delete('/suppliers/bulk-destroy', [App\Http\Controllers\SupplierController::class, 'bulkDestroy'])->name('suppliers.bulk-destroy');
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);
    Route::post('/purchases/{id}/complete', [App\Http\Controllers\PurchaseController::class, 'markComplete'])->name('purchases.complete');
    Route::delete('/defective-stock/bulk-destroy', [App\Http\Controllers\DefectiveStockController::class, 'bulkDestroy'])->name('defective-stock.bulk-destroy');
    Route::resource('defective-stock', App\Http\Controllers\DefectiveStockController::class);
    Route::post('/defective-stock/{id}/repair', [App\Http\Controllers\DefectiveStockController::class, 'repair'])->name('defective-stock.repair');
    Route::post('/defective-stock/{defectiveStock}/mark-good', [App\Http\Controllers\DefectiveStockController::class, 'markGood'])->name('defective-stock.mark-good');

    // Petroleum Station
    Route::prefix('petroleum')->name('petroleum.')->group(function () {
        Route::resource('tanks', App\Http\Controllers\Petroleum\FuelTankController::class)->except(['show']);
        Route::resource('pumps', App\Http\Controllers\Petroleum\FuelPumpController::class)->except(['show']);
        Route::resource('nozzles', App\Http\Controllers\Petroleum\FuelNozzleController::class)->except(['show']);
        Route::get('/day/close', [App\Http\Controllers\Petroleum\FuelDayController::class, 'closeForm'])->name('day.close');
        Route::post('/day/close', [App\Http\Controllers\Petroleum\FuelDayController::class, 'close'])->name('day.close.store');
        Route::get('/day/closings/{closing}', [App\Http\Controllers\Petroleum\FuelDayController::class, 'showClosing'])->name('day.closings.show');
        Route::resource('deliveries', App\Http\Controllers\Petroleum\FuelDeliveryController::class)->only(['index', 'create', 'store']);
    });
    
    // Expenses & Reports
    Route::delete('/expenses/bulk-destroy', [App\Http\Controllers\ExpenseController::class, 'bulkDestroy'])->name('expenses.bulk-destroy');
    Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [App\Http\Controllers\ReportController::class, 'indexPdf'])->name('reports.index.pdf');
    Route::get('/reports/profit-loss', [App\Http\Controllers\ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    Route::get('/reports/profit-loss/pdf', [App\Http\Controllers\ReportController::class, 'profitLossPdf'])->name('reports.profit_loss.pdf');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales/pdf', [App\Http\Controllers\ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
    Route::get('/reports/expenses', [App\Http\Controllers\ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('/reports/expenses/pdf', [App\Http\Controllers\ReportController::class, 'expensesPdf'])->name('reports.expenses.pdf');
    Route::get('/reports/stock-movement', [App\Http\Controllers\ReportController::class, 'stockMovement'])->name('reports.stock_movement');
    Route::get('/reports/stock-movement/pdf', [App\Http\Controllers\ReportController::class, 'stockMovementPdf'])->name('reports.stock_movement.pdf');
    Route::get('/reports/damaged-stock', [App\Http\Controllers\ReportController::class, 'damagedStock'])->name('reports.damaged_stock');
    Route::get('/reports/damaged-stock/pdf', [App\Http\Controllers\ReportController::class, 'damagedStockPdf'])->name('reports.damaged_stock.pdf');
    
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
        Route::delete('/payments/{payment}', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'destroy'])->name('payments.destroy');

        // CMS Routes
        Route::get('/cms', [App\Http\Controllers\SuperAdmin\CmsController::class, 'index'])->name('cms.index');
        Route::get('/cms/{page}/edit', [App\Http\Controllers\SuperAdmin\CmsController::class, 'edit'])->name('cms.edit');
        Route::post('/cms/{page}', [App\Http\Controllers\SuperAdmin\CmsController::class, 'update'])->name('cms.update');

        // Testimonials Routes
        Route::resource('testimonials', App\Http\Controllers\SuperAdmin\TestimonialController::class);
    });

});

Route::get('lang/{lang}', [\App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

Route::middleware(['auth', 'admin'])->group(function () {
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
        $results = [];
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'can_manage_products')) {
                try {
                    \Illuminate\Support\Facades\Schema::table('users', function ($table) {
                        $table->boolean('can_manage_products')->default(false)->after('is_active');
                    });
                    $results[] = "Safu ya can_manage_products imeongezwa kwenye users.";
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\DB::statement("ALTER TABLE `users` ADD COLUMN `can_manage_products` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`");
                    $results[] = "Safu ya can_manage_products imeongezwa kwa SQL.";
                }
            } else {
                $results[] = "Safu ya can_manage_products ipo tayari.";
            }

            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $results[] = "Database imesasishwa (Migrated): " . trim(\Illuminate\Support\Facades\Artisan::output());

            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            $results[] = "Cache imesafishwa kikamilifu.";

            return "<div style='font-family:sans-serif;padding:30px;max-width:600px;margin:20px auto;border:1px solid #10b981;border-radius:12px;background:#f0fdf4;'>
                <h2 style='color:#059669;margin-top:0;'>✅ Mfumo umesasishwa Kikamilifu!</h2>
                <ul style='line-height:1.8;'>" . implode('', array_map(fn($m) => "<li>$m</li>", $results)) . "</ul>
                <p><a href='/home' style='display:inline-block;padding:10px 20px;background:#059669;color:#fff;border-radius:8px;text-decoration:none;font-weight:bold;'>Fungua Dashboard ya Z-POS &rarr;</a></p>
            </div>";
        } catch (\Throwable $e) {
            return "<div style='font-family:sans-serif;padding:30px;max-width:600px;margin:20px auto;border:1px solid #ef4444;border-radius:12px;background:#fef2f2;'>
                <h2 style='color:#dc2626;'>Hitilafu: " . $e->getMessage() . "</h2>
            </div>";
        }
    });
});
