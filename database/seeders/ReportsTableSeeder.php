<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reports')->insert([
            [
                'outlet_id' => 1,
                'user_id' => 1,
                'type' => 'stock',
                'status' => 'waiting',
                'validated_by' => null,
                'validated_at' => null,
            ],
            [
                'outlet_id' => 2,
                'user_id' => 2,
                'type' => 'financial',
                'status' => 'waiting',
                'validated_by' => null,
                'validated_at' => null,
            ],
        ]);
    }
}