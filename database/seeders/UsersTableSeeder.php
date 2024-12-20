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
                'is_active' => true,
                'email' => 'manager@example.com',
                'alamat' => null,
                'no_telp' => '081234567890',
                'tanggal_bergabung' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Crew Outlet User',
                'username' => 'crewoutlet',
                'role' => 'crewoutlet',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email' => 'crew@example.com',
                'alamat' => null,
                'no_telp' => '081234567890',
                'tanggal_bergabung' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gudang User',
                'username' => 'gudang',
                'role' => 'gudang',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email' => 'gudang@example.com',
                'alamat' => null,
                'no_telp' => '081234567890',
                'tanggal_bergabung' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}