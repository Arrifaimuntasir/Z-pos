<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\User;
use App\Models\Sale;
use App\Notifications\DailyNoSalesNotification;
use App\Notifications\PaymentReminderNotification;
use Carbon\Carbon;

class SendPushNotifications extends Command
{
    protected $signature = 'push:daily-checks';
    protected $description = 'Send push notifications for 0 sales and payment reminders';

    public function handle()
    {
        $today = Carbon::today();
        
        $shops = Shop::all();
        
        foreach ($shops as $shop) {
            $owner = User::where('shop_id', $shop->id)->whereHas('roles', function($q) {
                $q->where('name', 'Admin');
            })->first();

            if (!$owner) continue;

            // Check if 0 sales today
            $salesCount = Sale::where('shop_id', $shop->id)
                ->whereDate('created_at', $today)
                ->count();
                
            if ($salesCount === 0) {
                $owner->notify(new DailyNoSalesNotification());
            }

            // Check payment reminder (e.g. if trial_ends_at or valid_until is past or near)
            // Assuming $shop has 'valid_until' date field or something similar. 
            // If the schema is different, we can adjust.
            // Let's assume standard payment structure, but if it doesn't exist we just wrap in try/catch or skip.
            // Z-POS usually uses 'package' and subscription status.
            if ($shop->package && $shop->package != 'free') {
                if (isset($shop->valid_until) && Carbon::parse($shop->valid_until)->isPast()) {
                    $owner->notify(new PaymentReminderNotification());
                }
            }
        }

        $this->info('Push notifications sent successfully.');
    }
}
