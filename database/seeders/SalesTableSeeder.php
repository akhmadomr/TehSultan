<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sales')->insert([
            [
                'outlet_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'total_price' => 50000.00,
                'sale_date' => now(),
                'payment_method' => 'cash',
                'status' => 'completed',
            ],
            [
                'outlet_id' => 2,
                'product_id' => 2,
                'quantity' => 1,
                'total_price' => 35000.00,
                'sale_date' => now(),
                'payment_method' => 'card',
                'status' => 'completed',
            ],
        ]);
    }
}
