<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'role_name' => 'Owner'],
            ['id' => 2, 'role_name' => 'Admin'],
            ['id' => 3, 'role_name' => 'MO'],
            ['id' => 4, 'role_name' => 'Customer'],
        ]);
    }
}
