<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HampersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hampers')->insert([
            ['id' => 1, 'hampers_name' => 'Paket A', 'hampers_price' => 650000, 'quantity' => 30, 'created_at' => now()],
            ['id' => 2, 'hampers_name' => 'Paket B', 'hampers_price' => 500000, 'quantity' => 20, 'created_at' => now()],
            ['id' => 3, 'hampers_name' => 'Paket C', 'hampers_price' => 350000, 'quantity' => 10, 'created_at' => now()]
        ]);
    }
}
