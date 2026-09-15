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
        Schema::table('sale_return_items', function (Blueprint $table) {
            $table->string('repair_status')->default('not_repaired')->after('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_return_items', function (Blueprint $table) {
            $table->dropColumn('repair_status');
        });
    }
};
