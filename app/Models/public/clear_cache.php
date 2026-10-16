<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Clear caches
\Artisan::call('cache:clear');
\Artisan::call('config:clear');
\Artisan::call('route:clear');
\Artisan::call('view:clear');

echo "Cache cleared successfully!";
