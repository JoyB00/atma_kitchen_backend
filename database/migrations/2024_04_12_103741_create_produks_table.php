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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penitip')->references('id')->on('penitips')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreignId('id_kategori')->references('id')->on('kategoris')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_produk');
            $table->integer('kuantitas');
            $table->float('harga_produk');
            $table->longText('foto_produk')->nullable();
            $table->longText('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
