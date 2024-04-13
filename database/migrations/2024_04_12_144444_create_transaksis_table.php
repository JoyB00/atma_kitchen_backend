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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_customer')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_pegawai')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_delivery')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_pesan');
            $table->date('tanggal_lunas');
            $table->date('tanggal_ambil');
            $table->string('metode_pembayarana');
            $table->string('bukti_pembayaran');
            $table->enum('status', ['paymentValid', 'notPaid', 'rejected', 'accepted', 'onProcess', 'readyForPickup', 'onDelivery']);
            $table->float('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
