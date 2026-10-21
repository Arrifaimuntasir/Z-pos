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

            // FROM: 'shop', 'warehouse', 'supplier', 'direct'
            $table->string('from_type', 30);
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');

            // TO: 'shop', 'warehouse'
            $table->string('to_type', 30);
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
