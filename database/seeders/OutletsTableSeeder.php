<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        DB::table('outlets')->insert([
            [
                'id' => 1,
                'nama' => 'Outlet Pusat',
                'alamat' => 'Jl. Utama No. 1, Kota',
                'foto' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'nama' => 'Outlet Cabang',
                'alamat' => 'Jl. Cabang No. 2, Kota',
                'foto' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}