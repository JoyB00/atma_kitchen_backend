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
        [
          "transaction_id" => 10,
          "product_id" => 1,
          "hampers_id" => null,
          "quantity" => 1,
          "price" => 850000,
          "total_price" => 850000
        ],
        [
          "transaction_id" => 11,
          "product_id" => 2,
          "hampers_id" => null,
          "quantity" => 1,
          "price" => 450000,
          "total_price" => 450000
        ],
        [
          "transaction_id" => 12,
          "product_id" => 3,
          "hampers_id" => null,
          "quantity" => 1,
          "price" => 550000,
          "total_price" => 550000
        ],
        [
          "transaction_id" => 13,
          "product_id" => 4,
          "hampers_id" => null,
          "quantity" => 1,
          "price" => 300000,
          "total_price" => 300000
        ],
      ]

    );
  }
}
