<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        DB::table('products')->insert([
            ['id' => 1, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Lapis Legit 1 Loyang', 'quantity' => 10, 'product_price' => 850000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],


            ['id' => 2, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Lapis Legit 1/2 Loyang', 'quantity' => 10, 'product_price' => 450000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 3, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Lapis Surabaya 1 Loyang', 'quantity' => 10, 'product_price' => 550000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 4, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Lapis Surabaya 1/2 Loyang', 'quantity' => 10, 'product_price' => 300000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 5, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Brownies 1 loyang', 'quantity' => 10, 'product_price' => 250000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 6, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Brownies 1/2 loyang', 'quantity' => 10, 'product_price' => 150000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 7, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Mandarin 1 Loyang', 'quantity' => 10, 'product_price' => 450000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 8, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Mandarin 1/2 Loyang', 'quantity' => 10, 'product_price' => 250000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 9, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Spikoe 1 loyang', 'quantity' => 10, 'product_price' => 350000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 10, 'consignor_id' => NULL, 'category_id' => 1, 'product_name' => 'Spikoe 1/2 loyang', 'quantity' => 9, 'product_price' => 200000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 11, 'consignor_id' => NULL, 'category_id' => 2, 'product_name' => 'Roti Sosis', 'quantity' => 10, 'product_price' => 180000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 12, 'consignor_id' => NULL, 'category_id' => 2, 'product_name' => 'Milk Bun', 'quantity' => 10, 'product_price' => 120000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 13, 'consignor_id' => NULL, 'category_id' => 2, 'product_name' => 'Roti Keju', 'quantity' => 10, 'product_price' => 150000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 14, 'consignor_id' => NULL, 'category_id' => 3, 'product_name' => 'Choco Creamy Latte', 'quantity' => 10, 'product_price' => 75000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 15, 'consignor_id' => NULL, 'category_id' => 3, 'product_name' => 'Matcha Creamy Latte', 'quantity' => 9, 'product_price' => 100000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 16, 'consignor_id' => 1, 'category_id' => 4, 'product_name' => 'Keripik Kentang 250 gr', 'quantity' => 10, 'product_price' => 75000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 17, 'consignor_id' => 1, 'category_id' => 4, 'product_name' => 'Kopi Luwak Bubuk 250 gr', 'quantity' => 10, 'product_price' => 250000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 18, 'consignor_id' => 2, 'category_id' => 4, 'product_name' => 'Matcha Organik Bubuk 100 gr', 'quantity' => 10, 'product_price' => 300000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()],

            ['id' => 19, 'consignor_id' => 2, 'category_id' => 4, 'product_name' => 'Chocolate Bar 100 gr', 'quantity' => 10, 'product_price' => 120000, 'product_pict' => "", 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'created_at' => now()]
        ]);
    }
}