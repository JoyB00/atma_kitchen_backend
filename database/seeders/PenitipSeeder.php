<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenitipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penitips')->insert([
            ['id' => 1, 'nama_penitip' => 'Celine', 'no_telp' => '0896439387777'],
            ['id' => 2, 'nama_penitip' => 'Olla', 'no_telp' => '081231232444'],
            ['id' => 3, 'nama_penitip' => 'Bala', 'no_telp' => '089123123234'],
            ['id' => 4, 'nama_penitip' => 'Christo', 'no_telp' => '08745612345'],
            ['id' => 5, 'nama_penitip' => 'Budi', 'no_telp' => '087456123789'],
        ]);
    }
}
