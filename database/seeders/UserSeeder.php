<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'id_role' => 4, 'fullName' => 'Bintang', 'email' => 'aaa@gmail.com', 'password' => 'bintang12345', 'phoneNumber' => '089643938', 'gender' => 'Pria', 'dateOfBirth' => '2024-03-24'],
            ['id' => 2, 'id_role' => 2, 'fullName' => 'Budianto', 'email' => 'budi@gmail.com', 'password' => 'budi12345', 'phoneNumber' => '0896439387777', 'gender' => 'Pria', 'dateOfBirth' => '2024-03-24'],
            ['id' => 3, 'id_role' => 3, 'fullName' => 'Sari', 'email' => 'sari@gmail.com', 'password' => 'Sari23456', 'phoneNumber' => '089678234123', 'gender' => 'Wanita', 'dateOfBirth' => '2024-03-18'],
            ['id' => 4, 'id_role' => 4, 'fullName' => 'Jonathan', 'email' => 'bbb@gmail.com', 'password' => 'jonathan12345', 'phoneNumber' => '085123456789', 'gender' => 'Wanita', 'dateOfBirth' => '2024-03-18'],
        ]);
    }
}
