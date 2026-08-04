<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$queries = [
    "Brands count" => function() { return \App\Models\Brand::count(); },
    "Models count" => function() { return \App\Models\Product::whereNotNull('model')->where('model', '!=', '')->distinct('model')->count('model'); },
    "Stock sum" => function() { return \App\Models\Product::sum('stock'); },
    "Cost sum" => function() { return \App\Models\Product::sum(\Illuminate\Support\Facades\DB::raw('cost_price * stock')); },
    "Selling sum" => function() { return \App\Models\Product::sum(\Illuminate\Support\Facades\DB::raw('selling_price * stock')); },
    "Expenses sum" => function() { return \App\Models\Expense::sum('amount'); },
];

foreach ($queries as $name => $callback) {
    $start = microtime(true);
    try {
        $callback();
        $time = microtime(true) - $start;
        echo "{$name}: {$time} seconds\n";
    } catch (\Exception $e) {
        echo "{$name} FAILED: " . $e->getMessage() . "\n";
    }
}
