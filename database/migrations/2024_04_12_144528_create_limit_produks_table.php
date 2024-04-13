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
        Schema::create('limit_produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->references('id')->on('produks')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_produksi');
            $table->integer('limit_produk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('limit_produks');
    }
};
