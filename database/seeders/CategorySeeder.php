<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 1, 'category_name' => 'Cake'],
            ['id' => 2, 'category_name' => 'Roti'],
            ['id' => 3, 'category_name' => 'Minuman'],
            ['id' => 4, 'category_name' => 'Titipan'],
        ]);
    }
}
