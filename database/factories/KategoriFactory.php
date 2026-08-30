<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kategori>
 */
class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_kategori' => fake()->unique()->word(),
            'deskripsi' => fake()->sentence(),
            'status' => 'aktif',
        ];
    }
}
