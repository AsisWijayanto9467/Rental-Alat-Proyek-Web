<?php

namespace Database\Factories;

use App\Models\Pengembalian;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengembalian>
 */
class PengembalianFactory extends Factory
{
    public function definition(): array
    {
        $penyewaan = Penyewaan::factory()->create();

        return [
            'penyewaan_id' => $penyewaan->id,
            'tanggal_pengembalian' => $penyewaan->tanggal_selesai,
            'diterima_oleh' => User::factory()->petugas(),
            'kondisi_alat' => fake()->sentence(),
            'terlambat_hari' => 0,
            'status' => 'diterima',
            'catatan' => null,
        ];
    }
}
