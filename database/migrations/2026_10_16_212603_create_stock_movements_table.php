<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('moved_by')->constrained('users')->onDelete('cascade');

            // FROM: null = shop floor/duka, filled = specific warehouse
            $table->enum('from_type', ['shop', 'warehouse']);
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');

            // TO: null = shop floor/duka, filled = specific warehouse
            $table->enum('to_type', ['shop', 'warehouse']);
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');

            $table->decimal('quantity', 10, 2);
            $table->text('notes')->nullable();
            $table->date('movement_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
