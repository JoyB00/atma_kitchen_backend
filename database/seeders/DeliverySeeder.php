<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliverySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('deliveries')->insert(
      [
        [
          'delivery_method' => 'Courier',
          'distance' => 10,
          'shipping_cost' => 100,
          'recipient_address' => '123 Main St, Springfield, IL 62701',
        ],
        [
          'delivery_method' => 'Pickup',
          'distance' => 0,
          'shipping_cost' => 0,
          'recipient_address' => '',
        ],
        [
          'delivery_method' => 'Courier',
          'distance' => 30,
          'shipping_cost' => 300,
          'recipient_address' => '789 Main St, Springfield, IL 62701',
        ]
      ]
    );
  }
}
