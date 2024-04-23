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
    DB::table('employees')->insert(
      [
        'user_id' => 2,
        'work_start_date' => '2024-03-24',
      ],
      [
        'user_id' => 3,
        'work_start_date' => '2024-03-18',
      ],
    );
  }
}
