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
    public function run(): void
    {
        DB::table('ingredient_procurements')->insert([

            ['id' => 1, 'employee_id' => 3, 'procurement_date' => '2024-03-04', 'total_price' => 800000],
            ['id' => 2, 'employee_id' => 3, 'procurement_date' => '2024-02-06', 'total_price' => 1500000],
        ]);
    }
}
