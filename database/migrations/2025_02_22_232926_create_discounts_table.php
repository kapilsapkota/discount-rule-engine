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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['percentage', 'fixed'])->comment('Defines discount calculation method');
            $table->decimal('amount', 10, 2)->comment('Percentage (1-100) or fixed value (>0)');
            $table->decimal('min_cart_total', 10, 2)->nullable()->comment('NULL = no minimum requirement');
            $table->dateTime('active_from')->nullable()->comment('NULL = start immediately');
            $table->dateTime('active_to')->nullable()->comment('NULL = never expire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
