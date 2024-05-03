<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('carts')->insert(
      [
        'customer_id' => 1,
        'balance_nominal' => 1000000,
        'bank_name' => 'BCA',
        'account_number' => '1234567890',
        'date' => '2021-01-02',
        'detail_information' => 'ksfjsjflksdfjkfdksl',
      ],
      [
        'customer_id' => 2,
        'balance_nominal' => 0,
        'bank_name' => 'BRI',
        'account_number' => '1234567890',
        'date' => '2021-01-01',
        'detail_information' => 'Lorem ipsum',
      ],
      [
        'customer_id' => 2,
        'balance_nominal' => 200000,
        'bank_name' => 'Gopay',
        'account_number' => '1234567890',
        'date' => '2021-01-03',
        'detail_information' => 'ksfjsjflksdfjkfdksl',
      ]
    );
  }
}
