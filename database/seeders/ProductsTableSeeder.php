<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Product 1',
                'description' => 'Description for product 1',
                'price' => 25000.00,
                'category' => 'Category A',
                'sku' => 'PRD001',
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for product 2',
                'price' => 35000.00,
                'category' => 'Category B',
                'sku' => 'PRD002',
            ],
        ]);
    }
}