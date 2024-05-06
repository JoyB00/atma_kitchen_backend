<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientProcurementSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('ingredient_procurements')->insert(
      [
        'employee_id' => 1,
        'procurement_date' => '2021-01-01',
        'total_price' => 1000000,
      ],
      [
        'employee_id' => 2,
        'procurement_date' => '2021-01-02',
        'total_price' => 2000000,
      ],
      [
        'employee_id' => 3,
        'procurement_date' => '2021-01-03',
        'total_price' => 3000000,
      ],
    );
  }
}
