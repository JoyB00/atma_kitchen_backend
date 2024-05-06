<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('customers')->insert(
      [
        'user_id' => 1,
        'point' => 100,
        'balance_nominal' => 1000000,
      ],
      [
        'user_id' => 4,
        'point' => 300,
        'balance_nominal' => 3000000,
      ]
    );
  }
}
