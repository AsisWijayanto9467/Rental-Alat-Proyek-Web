<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@rentalpro.com',
            'password' => Hash::make('admin123'),
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Pusat Gudang No. 1, Jakarta Selatan',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Petugas Gudang',
            'username' => 'petugas',
            'email' => 'petugas@rentalpro.com',
            'password' => Hash::make('petugas123'),
            'no_telepon' => '081234567891',
            'alamat' => 'Jl. Pusat Gudang No. 2, Jakarta Selatan',
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'no_telepon' => '081234567892',
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'role' => 'user',
            'status' => 'aktif',
        ]);
    }
}
