<?php

namespace Database\Seeders;

use App\Models\IngredientProcurements;
use App\Models\OtherProcurement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        CategorySeeder::run();
        ConsignorSeeder::run();
        ProductSeeder::run();
        RoleSeeder::run();
        HampersSeeder::run();
        IngredientSeeder::run();
        // IngredientProcurementSeeder::run();
        // IngredientsProcurementDetailsSeeder::run();
        // OtherProcurementSeeder::run();
    }
}
