<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('hampers', function (Blueprint $table) {
      $table->id();
      $table->string('hampers_name');
      $table->float('hampers_price');
      $table->integer('quantity');
      $table->longText('hampers_picture');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hampers');
  }
=======
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hampers', function (Blueprint $table) {
            $table->id();
            $table->string('hampers_name');
            $table->float('hampers_price');
            $table->integer('quantity');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hampers');
    }
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
};
