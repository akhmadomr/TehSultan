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
                'outlet_id' => 1, // matches Outlet Pusat
                'stock_item_id' => 1, // matches Stock Item 1
                'requested_by' => 1, // make sure user 1 exists
                'validated_by' => null,
                'request_amount' => 10,
                'status' => 'pending',
                'validated_at' => null,
                'notes' => 'Initial stock request',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 2, // matches Outlet Cabang
                'stock_item_id' => 2, // matches Stock Item 2
                'requested_by' => 1, // using existing user
                'validated_by' => null,
                'request_amount' => 20,
                'status' => 'pending',
                'validated_at' => null,
                'notes' => 'Monthly restock request',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}