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
    DB::table('ingredients')->insert(
      [
        'ingredient_name' => 'Butter',
        'quantity' => 1000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Creamer',
        'quantity' => 1000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Telur',
        'quantity' => 1000,
        'unit' => 'butir',
      ],
      [
        'ingredient_name' => 'Gula Pasir',
        'quantity' => 2000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Susu bubuk',
        'quantity' => 700,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Tepung Terigu',
        'quantity' => 400,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Garam',
        'quantity' => 2000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Coklat Bubuk',
        'quantity' => 4000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Selai Strawberry',
        'quantity' => 900,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Coklat Batang',
        'quantity' => 3500,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Minyak Goreng',
        'quantity' => 4500,
        'unit' => 'ml',
      ],
      [
        'ingredient_name' => 'Tepung Maizena',
        'quantity' => 800,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Baking Powder',
        'quantity' => 450,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Kacang Kenari',
        'quantity' => 5000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Ragi',
        'quantity' => 500,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Kuning Telur',
        'quantity' => 500,
        'unit' => 'buah',
      ],
      [
        'ingredient_name' => 'Susu Cair',
        'quantity' => 5000,
        'unit' => 'ml',
      ],
      [
        'ingredient_name' => 'Sosis Blackpepper',
        'quantity' => 300,
        'unit' => 'buah',
      ],
      [
        'ingredient_name' => 'Whipped Cream',
        'quantity' => 4000,
        'unit' => 'ml',
      ],
      [
        'ingredient_name' => 'Susu full cream',
        'quantity' => 3000,
        'unit' => 'ml',
      ],
      [
        'ingredient_name' => 'Keju Mozarella',
        'quantity' => 5000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Matcha Bubuk',
        'quantity' => 2000,
        'unit' => 'gr',
      ],
      [
        'ingredient_name' => 'Box 20x20 cm',
        'quantity' => 100,
        'unit' => 'buah',
      ],
      [
        'ingredient_name' => 'Box 20x10 cm',
        'quantity' => 100,
        'unit' => 'buah',
      ],
      [
        'ingredient_name' => 'Botol 1 liter',
        'quantity' => 500,
        'unit' => 'buah',
      ],
    );
  }
}
