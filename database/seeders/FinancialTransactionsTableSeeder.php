<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('financial_transactions')->insert([
            [
                'report_id' => 1,
                'amount' => 1000.00,
                'type' => 'income',
                'description' => 'Income description',
            ],
            [
                'report_id' => 2,
                'amount' => 500.00,
                'type' => 'outcome',
                'description' => 'Outcome description',
            ],
        ]);
    }
}
