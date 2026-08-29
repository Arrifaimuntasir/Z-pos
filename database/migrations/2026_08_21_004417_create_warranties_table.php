<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null');
            $table->string('warranty_number')->unique();
            $table->string('customer_name')->nullable();
            $table->string('product_name');
            $table->string('serial_number')->nullable();
            $table->string('duration'); // e.g., '12 Months'
            $table->date('start_date');
            $table->date('end_date');
            $table->text('conditions')->nullable();
            $table->string('design_theme')->default('1');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
