<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "<h1>Database updated successfully!</h1>";
    echo "<p>" . nl2br(\Illuminate\Support\Facades\Artisan::output()) . "</p>";
    echo "<p>Please delete this file for security.</p>";
} catch (\Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
