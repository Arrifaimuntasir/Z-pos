<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->nullable(); // e.g. "Hardware Store, Kariakoo"
            $table->string('avatar_initials', 5)->nullable(); // e.g. "AM"
            $table->string('avatar_color')->default('primary'); // primary, success, dark, warning
            $table->text('quote');
            $table->tinyInteger('rating')->default(5); // 1-5
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
