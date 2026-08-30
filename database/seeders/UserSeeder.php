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
            'nama' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'no_telepon' => '081234567892',
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'role' => 'user',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Siti Rahma',
            'username' => 'siti',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'no_telepon' => '081234567893',
            'alamat' => 'Jl. Sudirman No. 5, Jakarta',
            'role' => 'user',
            'status' => 'aktif',
        ]);
    }
}
