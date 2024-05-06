<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtherProcurementSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('other_procurements')->insert(
      [
        ['id' => 1, 'employee_id' => 1, 'ingredient_name' => 'Listrik', 'price' => 3890000, 'quantity' => 1, 'procurement_date' => '2024-02-05', 'total_price' => 389000],
        ['id' => 2, 'employee_id' => 1, 'ingredient_name' => 'Iuran RT', 'price' => 500000, 'quantity' => 1, 'procurement_date' => '2024-02-08', 'total_price' => 500000],
        ['id' => 3, 'employee_id' => 1, 'ingredient_name' => 'Bensin', 'price' => 900000, 'quantity' => 1, 'procurement_date' => '2024-01-08', 'total_price' => 900000],
        ['id' => 4, 'employee_id' => 1, 'ingredient_name' => 'Gas', 'price' => 2200000, 'quantity' => 1, 'procurement_date' => '2024-02-08', 'total_price' => 2200000],
        ['id' => 5, 'employee_id' => 1, 'ingredient_name' => 'Rapat Pegawai', 'price' => 250000, 'quantity' => 1, 'procurement_date' => '2024-02-08', 'total_price' => 250000]
      ]
    );
  }
}
