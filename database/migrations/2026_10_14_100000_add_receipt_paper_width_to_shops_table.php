<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumn('shops', 'receipt_paper_width')) {
                // Thermal receipt roll width in mm (58 or 80)
                $table->unsignedTinyInteger('receipt_paper_width')->default(80);
            }
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('receipt_paper_width');
        });
    }
};
