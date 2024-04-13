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
        Schema::create('pengadaan_bahan_lainnyas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pegawai')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_bahan');
            $table->float('harga');
            $table->date('tanggal_pengadaan_bahan_lainnya');
            $table->float('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_bahan_lainnyas');
    }
};
