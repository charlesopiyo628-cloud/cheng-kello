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
        Schema::create('fish_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // M, L, XL, SXL
            $table->string('description')->nullable();
            $table->decimal('unit_price', 10, 2); // 13000, 14000, 15000, 16500
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fish_categories');
    }
};
