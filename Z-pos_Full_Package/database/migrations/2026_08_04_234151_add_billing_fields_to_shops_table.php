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
            $table->boolean('is_active')->default(true)->after('logo_path');
            // Default valid until 14 days from now for trial
            $table->date('valid_until')->nullable()->after('is_active');
        });

        // Set existing shops to be valid for a long time since they are founders
        \Illuminate\Support\Facades\DB::table('shops')->update([
            'valid_until' => now()->addYears(10)
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'valid_until']);
        });
    }
};
