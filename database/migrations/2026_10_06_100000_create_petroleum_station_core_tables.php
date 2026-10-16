<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_tanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('name');
            $table->decimal('capacity', 14, 2);
            $table->decimal('current_stock', 14, 2)->default(0);
            $table->decimal('min_level', 14, 2)->default(0);
            $table->decimal('max_level', 14, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'name']);
        });

        Schema::create('fuel_pumps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['branch_id', 'number']);
        });

        Schema::create('fuel_nozzles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_pump_id')->constrained('fuel_pumps')->cascadeOnDelete();
            $table->foreignId('fuel_tank_id')->constrained('fuel_tanks')->restrictOnDelete();
            $table->unsignedInteger('number');
            $table->decimal('meter_reading', 16, 3)->default(0);
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['fuel_pump_id', 'number']);
        });

        Schema::create('fuel_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_tank_id')->constrained('fuel_tanks')->restrictOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->enum('type', ['opening', 'delivery', 'sale', 'adjustment', 'transfer_in', 'transfer_out', 'reconciliation']);
            $table->string('reference')->nullable();
            $table->decimal('qty_in', 14, 2)->default(0);
            $table->decimal('qty_out', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['fuel_tank_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_stock_movements');
        Schema::dropIfExists('fuel_nozzles');
        Schema::dropIfExists('fuel_pumps');
        Schema::dropIfExists('fuel_tanks');
    }
};
