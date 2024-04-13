<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_role')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama');
            $table->string('email');
            $table->string('password');
            $table->string('no_telp');
            $table->string('jenis_kelamin');
            $table->date('tanggal_lahir');
            $table->timestamps();
        });
    }

      /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
