<?php

namespace Database\Seeders;

use App\Models\Employees;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public static function run(): void
  {
    DB::table('transactions')->insert(
      [
        [
          "employee_id" => 4,
          "customer_id" => 1,
          "delivery_id" => 1, // deliver
          "order_date" => "2021-08-01",
          "paidoff_date" => "2021-08-02",
          "pickup_date" => "1970-01-01",
          "payment_method" => "Cash",
          "status" => "onProcess",
          "payment_evidence" => "payment.jpg",
          "used_point" => 0,
          "earned_point" => 105,
          "total_price" => 850000,
          "tip" => 0
        ],
        [
          "employee_id" => 4,
          "customer_id" => 1,
          "delivery_id" => 2, // pickup
          "order_date" => "2021-09-01",
          "paidoff_date" => "2021-09-02",
          "pickup_date" => "1970-01-01",
          "payment_method" => "Cash",
          "status" => "readyForPickup",
          "payment_evidence" => "",
          "used_point" => 0,
          "earned_point" => 65,
          "total_price" => 450000,
          "tip" => 0
        ],
        [
          "employee_id" => 5,
          "customer_id" => 2,
          "delivery_id" => 3, // deliver
          "order_date" => "2021-10-01",
          "paidoff_date" => "2021-10-02",
          "pickup_date" => "2021-10-04",
          "payment_method" => "BCA",
          "status" => "onProcess",
          "payment_evidence" => "image.webp",
          "used_point" => 0,
          "earned_point" => 85,
          "total_price" => 550000,
          "tip" => 0
        ],
        [
          "employee_id" => 4,
          "customer_id" => 2,
          "delivery_id" => 2, // pickup
          "order_date" => "2021-12-01",
          "paidoff_date" => "2021-12-02",
          "pickup_date" => "1970-01-01",
          "payment_method" => "BCA",
          "status" => "accepted",
          "payment_evidence" => "payment.jpg",
          "used_point" => 0,
          "earned_point" => 45,
          "total_price" => 300000,
          "tip" => 0
        ],
      ]
    );
  }
}
