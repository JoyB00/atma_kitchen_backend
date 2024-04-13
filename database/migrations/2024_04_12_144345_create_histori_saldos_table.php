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
        Schema::create('histori_saldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_customer')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('poin');
            $table->float('nominal_saldo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histori_saldos');
    }
};
