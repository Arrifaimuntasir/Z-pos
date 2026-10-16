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
        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumn('shops', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('shops', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('shops', 'tin_number')) {
                $table->string('tin_number')->nullable();
            }
            if (!Schema::hasColumn('shops', 'receipt_message')) {
                $table->text('receipt_message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'tin_number', 'receipt_message']);
        });
    }
};
