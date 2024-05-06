<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HampersDetailsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('hampers_details')->insert(
      [
        'hampers_id' => 1,
        'product_id' => 1,
        'ingredient_id' => 1, // kenapa ada ingredient? (christo)
      ],
    );
  }
}
