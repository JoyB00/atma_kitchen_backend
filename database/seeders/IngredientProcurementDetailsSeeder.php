<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientsProcurementDetailsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('ingredients_procurement_details')->insert(
      [
        'ingredient_id' => 1,
        'price' => 10000,
        'quantity' => 20,
        'total_price' => 200000,
      ],
      [
        'ingredient_id' => 2,
        'price' => 20000,
        'quantity' => 30,
        'total_price' => 600000,
      ],
      [
        'ingredient_id' => 10,
        'price' => 50000,
        'quantity' => 20,
        'total_price' => 1000000,
      ],
      [
        'ingredient_id' => 22,
        'price' => 20000,
        'quantity' => 25,
        'total_price' => 500000,
      ],
      [
        'ingredient_id' => 4,
        'price' => 10000,
        'quantity' => 3,
        'total_price' => 30000,
      ],
    );
  }
}
