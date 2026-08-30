<?php

namespace Database\Factories;

use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penyewaan>
 */
class PenyewaanFactory extends Factory
{
    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('-3 days', '+7 days');
        $selesai = (clone $mulai)->modify('+'.fake()->numberBetween(1, 14).' days');
        $totalHari = (int) (new \DateTimeImmutable($mulai->format('Y-m-d')))->diff(new \DateTimeImmutable($selesai->format('Y-m-d')))->days + 1;

        $subtotal = fake()->numberBetween(100000, 50000000);

        return [
            'kode_penyewaan' => 'PW-'.now()->format('Ymd').'-'.fake()->unique()->numberBetween(1, 9999),
            'user_id' => User::factory(),
            'tanggal_pengajuan' => now()->toDateString(),
            'tanggal_mulai' => $mulai->format('Y-m-d'),
            'tanggal_selesai' => $selesai->format('Y-m-d'),
            'total_hari' => $totalHari,
            'subtotal' => $subtotal,
            'denda' => 0,
            'total' => $subtotal,
            'status' => 'pending',
            'catatan' => null,
        ];
    }

    public function menungguPembayaran(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'menunggu_pembayaran',
        ]);
    }

    public function sedangDisewa(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sedang_disewa',
        ]);
    }
}
