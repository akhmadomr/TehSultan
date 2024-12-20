<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialTotalsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('financial_totals')->insert([
            [
                'outlet_id' => 1,
                'income' => 50000.00,
                'expense' => 10000.00,
                'net' => 40000.00,
                'total_income' => 50000.00,
                'total_expense' => 10000.00,
                'total_net' => 40000.00,
            ],
            [
                'outlet_id' => 2,
                'income' => 35000.00,
                'expense' => 5000.00,
                'net' => 30000.00,
                'total_income' => 35000.00,
                'total_expense' => 5000.00,
                'total_net' => 30000.00,
            ],
        ]);
    }
}
