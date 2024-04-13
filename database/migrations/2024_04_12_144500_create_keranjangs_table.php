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
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')->references('id')->on('transaksis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_produk')->references('id')->on('produks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_hampers')->references('id')->on('hampers')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('kuantitas');
            $table->float('harga_satuan');
            $table->float('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjangs');
    }
};
