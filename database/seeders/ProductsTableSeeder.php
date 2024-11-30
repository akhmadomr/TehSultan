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
                'nama' => 'Product 1',
                'is_default' => false,

            ],
            [
                'nama' => 'Product 2',
                'is_default' => false,

            ],
        ]);
    }
}