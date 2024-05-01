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
        'transaction_id' => 1,
        'product_id' => 1,
        'hampers_id' => null,
        'quantity' => 1,
        'price' => 100000,
        'total_price' => 100000,
      ],
      [
        'transaction_id' => 2,
        'product_id' => null,
        'hampers_id' => 1,
        'quantity' => 2,
        'price' => 200000,
        'total_price' => 400000,
      ],
      [
        'transaction_id' => 3,
        'product_id' => 2,
        'hampers_id' => null,
        'quantity' => 1,
        'price' => 150000,
        'total_price' => 150000,
      ]
    );
  }
}
