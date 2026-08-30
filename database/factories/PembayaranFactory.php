<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Penyewaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pembayaran>
 */
class PembayaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'penyewaan_id' => Penyewaan::factory(),
            'kode_pembayaran' => 'PAY-'.now()->format('Ymd').'-'.fake()->unique()->numberBetween(1, 9999),
            'tanggal_pembayaran' => now()->toDateString(),
            'jumlah' => fake()->numberBetween(100000, 50000000),
            'metode_pembayaran' => fake()->randomElement(['cash', 'transfer', 'qris']),
            'bukti_pembayaran' => null,
            'status' => 'pending',
            'catatan' => null,
        ];
    }

    public function diverifikasi(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now(),
        ]);
    }
}
