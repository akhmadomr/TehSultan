<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stock_transactions')->insert([
            [
                'report_id' => 1,
                'product_id' => 1,
                'stock_awal' => 100,
                'stock_tambah' => 10,
                'stock_total' => 110,
                'stock_sisa' => 90,
                'stock_terpakai' => 20,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'report_id' => 2,
                'product_id' => 2,
                'stock_awal' => 200,
                'stock_tambah' => 20,
                'stock_total' => 220,
                'stock_sisa' => 180,
                'stock_terpakai' => 40,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}