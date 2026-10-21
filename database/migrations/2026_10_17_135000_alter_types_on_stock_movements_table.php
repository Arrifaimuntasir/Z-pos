<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `from_type` VARCHAR(50) NOT NULL");
            DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `to_type` VARCHAR(50) NOT NULL");
        } catch (\Throwable $e) {
            // Table might not exist yet on fresh installations
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed
    }
};
