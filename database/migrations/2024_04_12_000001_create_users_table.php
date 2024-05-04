<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
=======
    /**
     *  'id_role', 'nama', 'email', 'password', 'no_telp', 'jenis_kelamin', 'tanggal_lahir'
     * Run the migrations.
     */
>>>>>>> a14fd3df5dce9a529a1509fb9cbd453f6ac0f1b7
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->foreignId('id_role')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('phone_number');
            $table->string('gender');
            $table->date('birth_date');
=======
            $table->string('fullName');
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('phoneNumber');
            $table->string('gender')->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('verify_key')->nullable();
            $table->integer('active')->nullable();
            $table->timestamp('email_verified_at')->nullable();
>>>>>>> a14fd3df5dce9a529a1509fb9cbd453f6ac0f1b7
            $table->timestamps();
            $table->rememberToken();
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
