<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('financial_items')->insert([
            [
                'report_id' => 1,
                'outlet_id' => 1,
                'type' => 'income',
                'item_name' => 'Sales Revenue',
                'price' => 25000.00,
                'quantity' => 2,
                'total' => 50000.00,
            ],
            [
                'report_id' => 1,
                'outlet_id' => 1,
                'type' => 'expense',
                'item_name' => 'Supplies',
                'price' => 10000.00,
                'quantity' => 1,
                'total' => 10000.00,
            ],
        ]);
    }
}
