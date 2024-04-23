<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('balance_histories')->insert(
      [
        [
          'employee_id' => 1,
          'attendance_date' => '2021-01-02',
          'is_bolos' => 1,
        ],
        [
          'employee_id' => 1,
          'attendance_date' => '2021-01-03',
          'is_bolos' => 1,
        ],
        [
          'employee_id' => 2,
          'attendance_date' => '2021-01-01',
          'is_bolos' => 1,
        ],
        [
          'employee_id' => 2,
          'attendance_date' => '2021-01-03',
          'is_bolos' => 1,
        ],
      ],
    );
  }
}
