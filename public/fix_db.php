<?php
// Fix: Add repair_status column to sale_return_items table on cPanel
$appPath = '/home/loufaypy/zpos_app';

// Load Laravel environment
require $appPath . '/vendor/autoload.php';
$app = require $appPath . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo '<h2>Database Fix Tool</h2><pre>';

// Check if column exists
if (Schema::hasColumn('sale_return_items', 'repair_status')) {
    echo "✅ Column 'repair_status' already exists in sale_return_items.\n";
} else {
    echo "❌ Column 'repair_status' MISSING - Adding it now...\n";
    Schema::table('sale_return_items', function($table) {
        $table->string('repair_status')->default('not_repaired')->after('condition');
    });
    echo "✅ Column 'repair_status' added successfully!\n";
}

// Clear view cache
echo "\nClearing view cache...\n";
$cacheDir = $appPath . '/storage/framework/views';
$count = 0;
foreach (glob($cacheDir . '/*.php') as $f) {
    @unlink($f);
    $count++;
}
echo "✅ Cleared $count cached view files.\n";

echo "\n✅ ALL DONE! Refresh the Defective Items page now.\n";
echo '</pre>';
