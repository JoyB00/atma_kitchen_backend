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
        Schema::create('detail_pengadaan_bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bahan_baku')->references('id')->on('bahan_bakus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_pengadaan_bahan_baku')->references('id')->on('pengadaan_bahan_bakus')->onDelete('cascade')->onUpdate('cascade');
            $table->float('harga_satuan');
            $table->integer('kuantitas');
            $table->float('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengadaan_bahan_bakus');
    }
};
