<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'users',
            'categories',
            'brands',
            'units',
            'customers',
            'suppliers',
            'expenses',
            'purchases',
            'purchase_items',
            'sales',
            'sale_items',
            'products'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'shop_id')) {
                    $table->foreignId('shop_id')->nullable()->constrained('shops')->cascadeOnDelete();
                }
            });
        }

        // Create a default shop if none exists
        $defaultShopId = \Illuminate\Support\Facades\DB::table('shops')->insertGetId([
            'name' => 'Zamar Store',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign all existing records to the default shop
        foreach ($tables as $table) {
            \Illuminate\Support\Facades\DB::table($table)->update(['shop_id' => $defaultShopId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'categories',
            'brands',
            'units',
            'customers',
            'suppliers',
            'expenses',
            'purchases',
            'purchase_items',
            'sales',
            'sale_items',
            'products'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'shop_id')) {
                    $table->dropForeign(['shop_id']);
                    $table->dropColumn('shop_id');
                }
            });
        }
    }
};
