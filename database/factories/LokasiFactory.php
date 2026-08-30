<?php

namespace Database\Factories;

use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lokasi>
 */
class LokasiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_lokasi' => fake()->unique()->city().' Gudang',
            'alamat' => fake()->address(),
            'keterangan' => fake()->sentence(),
            'status' => 'aktif',
        ];
    }
}
