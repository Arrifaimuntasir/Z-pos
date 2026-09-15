<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$branchId = 1;
$productId = 1; // Assuming product 1 exists
$qty = 2;

DB::table('branch_product')->where('product_id', $productId)->delete();
$before = DB::table('branch_product')->where('branch_id', $branchId)->where('product_id', $productId)->first();
echo "Before: " . json_encode($before) . "\n";

DB::table('branch_product')->updateOrInsert(
    ['branch_id' => $branchId, 'product_id' => $productId],
    ['quantity' => DB::raw('quantity + ' . $qty)]
);

$after = DB::table('branch_product')->where('branch_id', $branchId)->where('product_id', $productId)->first();
echo "After: " . json_encode($after) . "\n";
