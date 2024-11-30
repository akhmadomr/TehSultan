<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Manager User',
                'username' => 'manager',
                'role' => 'manager',
                'password' => Hash::make('password'),
            ],
            [
                'nama' => 'Crew Outlet User',
                'username' => 'crewoutlet',
                'role' => 'crewoutlet',
                'password' => Hash::make('password'),
            ],
            [
                'nama' => 'Gudang User',
                'username' => 'gudang',
                'role' => 'gudang',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}