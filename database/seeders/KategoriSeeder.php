<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            ['id' => 1, 'nama_kategori' => 'Cake'],
            ['id' => 2, 'nama_kategori' => 'Roti'],
            ['id' => 3, 'nama_kategori' => 'Minuman'],
            ['id' => 4, 'nama_kategori' => 'Titipan'],
        ]);
    }
}
