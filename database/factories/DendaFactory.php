<?php

namespace Database\Factories;

use App\Models\Denda;
use App\Models\Penyewaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Denda>
 */
class DendaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'penyewaan_id' => Penyewaan::factory(),
            'pengembalian_id' => null,
            'jenis_denda' => fake()->randomElement(['terlambat', 'kerusakan', 'kehilangan']),
            'jumlah' => fake()->numberBetween(50000, 5000000),
            'alasan' => fake()->sentence(),
            'status' => 'pending',
        ];
    }
}
