<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateDataToMultiBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-data-to-multi-branch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration to Multi-Branch architecture...');

        $shops = \App\Models\Shop::all();
        foreach ($shops as $shop) {
            $this->info("Migrating Shop ID: {$shop->id}");

            // 1. Create a Main Branch
            $branch = \App\Models\Branch::firstOrCreate(
                ['shop_id' => $shop->id, 'name' => 'Main Branch'],
                ['is_active' => true]
            );

            // 2. Update Users
            \App\Models\User::where('shop_id', $shop->id)
                ->whereNull('branch_id')
                ->update(['branch_id' => $branch->id]);

            // 3. Update Sales
            \Illuminate\Support\Facades\DB::table('sales')
                ->where('shop_id', $shop->id)
                ->whereNull('branch_id')
                ->update(['branch_id' => $branch->id]);

            // 4. Update Expenses
            \Illuminate\Support\Facades\DB::table('expenses')
                ->where('shop_id', $shop->id)
                ->whereNull('branch_id')
                ->update(['branch_id' => $branch->id]);

            // 5. Update Purchases
            \Illuminate\Support\Facades\DB::table('purchases')
                ->where('shop_id', $shop->id)
                ->whereNull('branch_id')
                ->update(['branch_id' => $branch->id]);

            // 6. Migrate Product Stock to branch_product table
            $products = \App\Models\Product::withoutGlobalScopes()->where('shop_id', $shop->id)->get();
            foreach ($products as $product) {
                \Illuminate\Support\Facades\DB::table('branch_product')->updateOrInsert(
                    ['branch_id' => $branch->id, 'product_id' => $product->id],
                    ['quantity' => $product->stock ?? 0]
                );
            }
            
            $this->info("Shop ID: {$shop->id} migration completed.");
        }

        $this->info('All data migrated successfully!');
    }
}
