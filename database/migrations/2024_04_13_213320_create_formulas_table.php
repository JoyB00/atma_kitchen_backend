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
        Schema::create('formulas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->int('quantity');

            //foreign key ke tabel bahan baku
            //TODO: FIX KALAU SALAH
            $table->foreignId('id_bahan_baku')->references('id')->on('bahan_bakus')->onDelete('cascade')->onUpdate('cascade');

            //foreign key ke tabel produk
            //TODO: FIX KALAU SALAH
            $table->foreignId('id_produk')->references('id')->on('produks')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulas');
    }
};
