<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('ingredients')->insert([
      ['id' => 1,  'ingredient_name' => 'Butter', 'quantity' => 1000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 2,  'ingredient_name' => 'Creamer', 'quantity' => 1000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 3,  'ingredient_name' => 'Telur', 'quantity' => 1000, 'unit' => 'butir', 'created_at' => now()],
      ['id' => 4,  'ingredient_name' => 'Gula Pasir', 'quantity' => 2000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 5,  'ingredient_name' => 'Susu Bubuk', 'quantity' => 700, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 6,  'ingredient_name' => 'Tepung Terigu', 'quantity' => 400, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 7,  'ingredient_name' => 'Garam', 'quantity' => 2000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 8,  'ingredient_name' => 'Coklat Bubuk', 'quantity' => 4000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 9,  'ingredient_name' => 'Selai Strawberry', 'quantity' => 900, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 10, 'ingredient_name' => 'Coklat Batang', 'quantity' => 3500, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 11, 'ingredient_name' => 'Minyak Goreng', 'quantity' => 4500, 'unit' => 'ml', 'created_at' => now()],
      ['id' => 12, 'ingredient_name' => 'Tepung Maizena', 'quantity' => 800, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 13, 'ingredient_name' => 'Baking Powder', 'quantity' => 450, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 14, 'ingredient_name' => 'Kacang Kenari', 'quantity' => 5000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 15, 'ingredient_name' => 'Ragi', 'quantity' => 500, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 16, 'ingredient_name' => 'Kuning Telur', 'quantity' => 500, 'unit' => 'buah', 'created_at' => now()],
      ['id' => 17, 'ingredient_name' => 'Susu Cair', 'quantity' => 5000, 'unit' => 'ml', 'created_at' => now()],
      ['id' => 18, 'ingredient_name' => 'Sosis Blackpapper', 'quantity' => 300, 'unit' => 'buah', 'created_at' => now()],
      ['id' => 19, 'ingredient_name' => 'Whipped Cream', 'quantity' => 4000, 'unit' => 'ml', 'created_at' => now()],
      ['id' => 20, 'ingredient_name' => 'Susu Full Cream', 'quantity' => 3000, 'unit' => 'ml', 'created_at' => now()],
      ['id' => 21, 'ingredient_name' => 'Keju mozzarella', 'quantity' => 5000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 22, 'ingredient_name' => 'Matcha Bubuk', 'quantity' => 2000, 'unit' => 'gr', 'created_at' => now()],
      ['id' => 23, 'ingredient_name' => 'Box 20x20 cm', 'quantity' => 100, 'unit' => 'buah', 'created_at' => now()],
      ['id' => 24, 'ingredient_name' => 'Box 20x10 cm', 'quantity' => 100, 'unit' => 'buah', 'created_at' => now()],
      ['id' => 25, 'ingredient_name' => 'Botol 1 liter', 'quantity' => 500, 'unit' => 'buah', 'created_at' => now()],
      ['id' => 26, 'ingredient_name' => 'Box Premium', 'quantity' => 400, 'unit' => 'buah', 'created_at' => now()],
      ['id' => 27, 'ingredient_name' => 'Kartu Ucapan', 'quantity' => 5000, 'unit' => 'buah', 'created_at' => now()],
      ['id' => 28, 'ingredient_name' => 'Tas Spunbond', 'quantity' => 200, 'unit' => 'buah', 'created_at' => now()],

    ],);
  }
}
