<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for shops expiring in 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->addDays(3)->format('Y-m-d');
        
        $shops = \App\Models\Shop::with('users')
            ->whereDate('valid_until', $targetDate)
            ->where('is_active', true)
            ->get();

        foreach ($shops as $shop) {
            foreach ($shop->users as $user) {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SubscriptionReminder($shop, 3));
            }
        }

        $this->info(count($shops) . ' reminder(s) sent.');
    }
}
