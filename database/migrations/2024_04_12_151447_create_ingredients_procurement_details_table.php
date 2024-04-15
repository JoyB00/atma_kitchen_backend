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
        Schema::create('ingredients_procurement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ingredient_procurements_id')->references('id')->on('ingredient_procurements')->onDelete('cascade')->onUpdate('cascade');
            $table->float('price');
            $table->integer('quantity');
            $table->float('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients_procurement_details');
    }
};
