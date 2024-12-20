<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,        // Users should be first as they might be referenced
            OutletsTableSeeder::class,      // Outlets might be referenced by products
            ProductsTableSeeder::class, 
            ReportsTableSeeder::class,   
            StockItemsTableSeeder::class,   // Correct position after Products and Outlets
            StockTransactionsTableSeeder::class, // Transactions need stock items
            StockRequestsTableSeeder::class,     // Requests need stock items
            StockSeeder::class,            // Added StockSeeder
   // Reports might depend on all previous data
        ]);
    }
}
