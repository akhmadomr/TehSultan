<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('outlets')->insert([
            [
                'nama' => 'Outlet 1',
                'alamat' => 'Address 1',
                'foto' => null,
            ],
            [
                'nama' => 'Outlet 2',
                'alamat' => 'Address 2',
                'foto' => null,
            ],
        ]);
    }
}