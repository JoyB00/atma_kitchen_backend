<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientProcurementDetails extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredients_prcmnt_dtl')->insert([
            ['id' => 1, 'ingredient_procurement_id' => 1, 'ingredient_id' => 1, 'price' => 10000, 'quantity' => 20, 'total_price' => 200000],
            ['id' => 2, 'ingredient_procurement_id' => 1, 'ingredient_id' => 2, 'price' => 20000, 'quantity' => 30, 'total_price' => 600000],
            ['id' => 3, 'ingredient_procurement_id' => 2, 'ingredient_id' => 10, 'price' =>  50000, 'quantity' => 20, 'total_price' => 1000000],
            ['id' => 4, 'ingredient_procurement_id' => 2, 'ingredient_id' => 22, 'price' =>  20000, 'quantity' => 25, 'total_price' => 500000],
            ['id' => 5, 'ingredient_procurement_id' => 2, 'ingredient_id' => 4, 'price' => 10000, 'quantity' => 3, 'total_price' => 30000],
        ]);
    }
}
