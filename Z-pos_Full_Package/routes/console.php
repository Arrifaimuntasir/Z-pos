<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Run every 6 hours
Schedule::command('push:daily-checks')->everySixHours();

// Daily end-of-day report + no-sales reminder, every day at 6pm
Schedule::command("report:daily")->dailyAt("18:00");
