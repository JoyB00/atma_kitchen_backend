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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pegawai_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
            $table->date('order_date');
            $table->date('paidoff_date');
            $table->date('pickup_date');
            $table->string('payment_method');
            $table->enum('status', ['paymentValid', 'notPaid', 'rejected', 'accepted', 'onProcess', 'readyForPickup', 'onDelivery']);
            $table->longText('payment_evidence');
            $table->integer('used_point');
            $table->integer('earned_point');
            $table->float('total_price');
            $table->float('tip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
