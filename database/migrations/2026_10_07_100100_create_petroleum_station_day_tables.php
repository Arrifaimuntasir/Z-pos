<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_daily_openings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->date('business_date');
            $table->decimal('opening_cash', 15, 2)->default(0);
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['branch_id', 'business_date']);
        });

        Schema::create('fuel_daily_opening_tanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_daily_opening_id')->constrained('fuel_daily_openings')->cascadeOnDelete();
            $table->foreignId('fuel_tank_id')->constrained('fuel_tanks')->restrictOnDelete();
            $table->decimal('litres', 15, 3);
            $table->unique(['fuel_daily_opening_id', 'fuel_tank_id'], 'fuel_open_tank_unique');
        });

        Schema::create('fuel_pump_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_daily_opening_id')->constrained('fuel_daily_openings')->cascadeOnDelete();
            $table->foreignId('fuel_nozzle_id')->constrained('fuel_nozzles')->restrictOnDelete();
            $table->enum('reading_type', ['opening', 'closing']);
            $table->decimal('meter', 16, 3);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recorded_at');
            $table->unique(['fuel_daily_opening_id', 'fuel_nozzle_id', 'reading_type'], 'fuel_readings_unique_per_day');
        });

        Schema::create('fuel_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_tank_id')->constrained('fuel_tanks')->restrictOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->date('delivery_date');
            $table->time('delivery_time')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('delivery_note_number')->nullable();
            $table->string('driver')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('fuel_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_daily_opening_id')->constrained('fuel_daily_openings')->restrictOnDelete();
            $table->string('receipt_number');
            $table->date('business_date');
            $table->foreignId('fuel_pump_id')->constrained('fuel_pumps')->restrictOnDelete();
            $table->foreignId('fuel_nozzle_id')->constrained('fuel_nozzles')->restrictOnDelete();
            $table->foreignId('fuel_tank_id')->constrained('fuel_tanks')->restrictOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->decimal('litres', 15, 3);
            $table->decimal('price_per_litre', 15, 2);
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'card', 'mobile_money', 'bank', 'other']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['shop_id', 'receipt_number']);
            $table->index(['branch_id', 'business_date']);
        });

        Schema::create('fuel_daily_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_daily_opening_id')->unique()->constrained('fuel_daily_openings')->restrictOnDelete();
            $table->date('business_date');
            $table->decimal('expected_cash', 15, 2)->default(0);
            $table->decimal('physical_cash', 15, 2)->default(0);
            $table->decimal('cash_variance', 15, 2)->default(0);
            $table->enum('cash_status', ['balanced', 'over', 'short']);
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('fuel_daily_closing_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_daily_closing_id')->constrained('fuel_daily_closings')->cascadeOnDelete();
            $table->enum('line_type', ['tank', 'nozzle']);
            $table->foreignId('fuel_tank_id')->nullable()->constrained('fuel_tanks')->restrictOnDelete();
            $table->foreignId('fuel_nozzle_id')->nullable()->constrained('fuel_nozzles')->restrictOnDelete();
            $table->string('label');
            $table->decimal('opening', 15, 3)->default(0);
            $table->decimal('delivered', 15, 3)->default(0);
            $table->decimal('sold', 15, 3)->default(0);
            $table->decimal('expected', 15, 3)->default(0);
            $table->decimal('physical', 15, 3)->default(0);
            $table->decimal('variance', 15, 3)->default(0);
            $table->enum('status', ['balanced', 'over', 'short']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_daily_closing_lines');
        Schema::dropIfExists('fuel_daily_closings');
        Schema::dropIfExists('fuel_sales');
        Schema::dropIfExists('fuel_deliveries');
        Schema::dropIfExists('fuel_pump_readings');
        Schema::dropIfExists('fuel_daily_opening_tanks');
        Schema::dropIfExists('fuel_daily_openings');
    }
};
