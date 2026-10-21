<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_movements', 'from_branch_id')) {
                $table->unsignedBigInteger('from_branch_id')->nullable()->after('from_warehouse_id');
                $table->index('from_branch_id', 'sm_from_branch_idx');
            }
            if (!Schema::hasColumn('stock_movements', 'to_branch_id')) {
                $table->unsignedBigInteger('to_branch_id')->nullable()->after('to_warehouse_id');
                $table->index('to_branch_id', 'sm_to_branch_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            if (Schema::hasColumn('stock_movements', 'from_branch_id')) {
                $table->dropIndex('sm_from_branch_idx');
                $table->dropColumn('from_branch_id');
            }
            if (Schema::hasColumn('stock_movements', 'to_branch_id')) {
                $table->dropIndex('sm_to_branch_idx');
                $table->dropColumn('to_branch_id');
            }
        });
    }
};
