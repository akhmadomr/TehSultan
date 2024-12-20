<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        DB::table('reports')->insert([
            [
                'outlet_id' => 1,
                'user_id' => 1,
                'type' => 'financial',
                'status' => 'waiting',
                'shift' => 'pagi',
                'validated_by' => null,
                'validated_at' => null,
                'difference' => 15000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'outlet_id' => 1,
                'user_id' => 1,
                'type' => 'stock',
                'status' => 'waiting',
                'shift' => 'pagi',
                'validated_by' => null,
                'validated_at' => null,
                'difference' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'outlet_id' => 1,
                'user_id' => 2,
                'type' => 'financial',
                'status' => 'validated',
                'shift' => 'siang',
                'validated_by' => 1,
                'validated_at' => $now,
                'difference' => -5000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'outlet_id' => 1,
                'user_id' => 2,
                'type' => 'stock',
                'status' => 'validated',
                'shift' => 'siang',
                'validated_by' => 1,
                'validated_at' => $now,
                'difference' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}