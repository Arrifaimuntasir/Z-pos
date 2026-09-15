<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Expense;
use App\Mail\DailyBusinessReport;
use App\Mail\NoSalesReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyBusinessReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Email each shop owner their end-of-day sales report, and a reminder if they made no sales today';

    public function handle()
    {
        $today = Carbon::today();
        $sent = 0;
        $reminders = 0;

        $shops = Shop::all();

        foreach ($shops as $shop) {
            $owner = User::where('shop_id', $shop->id)->whereHas('roles', function ($q) {
                $q->where('name', 'Administrator');
            })->first();

            if (!$owner || !$owner->email) {
                continue;
            }

            $sales = Sale::where('shop_id', $shop->id)
                ->whereDate('created_at', $today)
                ->get();

            $salesCount = $sales->count();
            $totalRevenue = $sales->sum('total_amount');

            $saleIds = $sales->pluck('id');

            $itemsSold = SaleItem::whereIn('sale_id', $saleIds)->sum('quantity');

            $grossProfit = SaleItem::whereIn('sale_id', $saleIds)
                ->selectRaw('SUM((unit_price - unit_cost) * quantity) as profit')
                ->value('profit') ?? 0;

            $totalExpenses = Expense::where('shop_id', $shop->id)
                ->whereDate('expense_date', $today)
                ->sum('amount');

            $topProducts = SaleItem::whereIn('sale_id', $saleIds)
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->selectRaw('products.name as name, SUM(sale_items.quantity) as qty')
                ->groupBy('products.name')
                ->orderByDesc('qty')
                ->limit(3)
                ->get();

            Mail::to($owner->email)->send(new DailyBusinessReport(
                $shop,
                $owner,
                $today,
                $salesCount,
                $totalRevenue,
                $itemsSold,
                $grossProfit,
                $totalExpenses,
                $topProducts
            ));
            $sent++;

            if ($salesCount === 0) {
                Mail::to($owner->email)->send(new NoSalesReminder($shop, $owner));
                $reminders++;
            }
        }

        $this->info("{$sent} daily report(s) sent, {$reminders} no-sales reminder(s) sent.");
    }
}
