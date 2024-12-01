<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockItem;

class StockItemSeeder extends Seeder
{
    public function run(): void
    {
        $defaultItems = [
            'Cup', 'Teh', 'Gula', 'Creamer', 'Lemon',
            'Air Gallon', 'Sedotan', 'Tusuk', 'Kecap',
            'Pentol', 'Tahu', 'Urat', 'Puyuh',
            'Kresek Cup', 'Kresek Los', 'Plastik 1kg',
            'Plastik 1/2kg', 'Sealer Cup'
        ];

        foreach ($defaultItems as $item) {
            StockItem::create([
                'name' => $item,
                'is_default' => true
            ]);
        }
    }
}