<?php

namespace Database\Factories;

use App\Models\AlatProyek;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlatProyek>
 */
class AlatProyekFactory extends Factory
{
    public function definition(): array
    {
        $stok = fake()->numberBetween(1, 10);

        return [
            'kategori_id' => Kategori::factory(),
            'lokasi_id' => Lokasi::factory(),
            'kode_alat' => fake()->unique()->bothify('ALAT-####'),
            'nama_alat' => fake()->words(3, true),
            'deskripsi' => fake()->sentence(),
            'harga_sewa_harian' => fake()->numberBetween(100000, 5000000),
            'stok' => $stok,
            'stok_tersedia' => $stok,
            'kondisi' => 'baik',
            'status' => 'tersedia',
        ];
    }
}
