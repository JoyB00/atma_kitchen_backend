<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsignorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        DB::table('consignors')->insert([
            ['id' => 1, 'consignor_name' => 'Celine', 'phone_number' => '0896439387777'],
            ['id' => 2, 'consignor_name' => 'Olla', 'phone_number' => '081231232444'],
            ['id' => 3, 'consignor_name' => 'Bala', 'phone_number' => '089123123234'],
            ['id' => 4, 'consignor_name' => 'Christo', 'phone_number' => '08745612345'],
            ['id' => 5, 'consignor_name' => 'Budi', 'phone_number' => '087456123789'],
        ]);
    }
}
