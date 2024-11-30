<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stock_requests')->insert([
            [
                'outlet_id' => 1,
                'product_id' => 1,
                'requested_by' => 1,
                'validated_by' => null,
                'request_amount' => 10,
                'status' => 'pending',
                'validated_at' => null,
            ],
            [
                'outlet_id' => 2,
                'product_id' => 2,
                'requested_by' => 2,
                'validated_by' => null,
                'request_amount' => 20,
                'status' => 'pending',
                'validated_at' => null,
            ],
        ]);
    }
}