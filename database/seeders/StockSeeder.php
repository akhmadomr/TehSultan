<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Outlet;
use App\Models\Report;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        $outlet = Outlet::first();
        $report = Report::first();

        Stock::create([
            'outlet_id' => $outlet->id,
            'report_id' => $report->id,
            'item_name' => 'Sample Item',
            'initial_stock' => 100,
            'added_stock' => 50,
            'total_stock' => 150,
            'remaining_stock' => 75,
            'used_stock' => 75,
            'status' => 'completed'
        ]);
    }
}
