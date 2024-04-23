<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('addresses')->insert([
      'customer_id' => 1,
      'subdistrict' => 'Kebon Jeruk',
      'city' => 'Jakarta Barat',
      'postal_code' => '11530',
      'complete_address' => 'Jl. Raya Kebon Jeruk No. 1',
    ]);
  }
}
