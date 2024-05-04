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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->foreignId('consignor_id')->nullable()->references('id')->on('consignors')->onDelete('cascade')->onUpdate('cascade');
=======
            $table->foreignId('custodian_id')->references('id')->on('custodians')->onDelete('cascade')->onUpdate('cascade')->nullable();
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('product_name');
            $table->integer('quantity');
            $table->float('product_price');
<<<<<<< HEAD
            $table->longText('product_picture');
=======
            $table->longText('product_pict');
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
