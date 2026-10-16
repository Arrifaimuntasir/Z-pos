<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page'); // about, pricing, features, contact, privacy, terms, cookies
            $table->string('key');  // hero_title, starter_price, etc.
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea
            $table->string('label'); // Human-readable label
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['page', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_settings');
    }
};
