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
        Schema::table('warranties', function (Blueprint $table) {
            if (!Schema::hasColumn('warranties', 'region')) {
                $table->string('region')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('warranties', 'gender')) {
                $table->string('gender')->nullable()->after('region');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warranties', function (Blueprint $table) {
            $table->dropColumn(['region', 'gender']);
        });
    }
};
