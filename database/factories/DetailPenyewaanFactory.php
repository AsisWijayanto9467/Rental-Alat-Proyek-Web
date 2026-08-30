<?php

namespace Database\Factories;

use App\Models\AlatProyek;
use App\Models\DetailPenyewaan;
use App\Models\Penyewaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DetailPenyewaan>
 */
class DetailPenyewaanFactory extends Factory
{
    public function definition(): array
    {
        $alat = AlatProyek::factory()->create();
        $jumlah = fake()->numberBetween(1, 3);

        return [
            'penyewaan_id' => Penyewaan::factory(),
            'alat_id' => $alat->id,
            'jumlah' => $jumlah,
            'harga_sewa' => $alat->harga_sewa_harian,
            'subtotal' => $alat->harga_sewa_harian * $jumlah,
            'kondisi_sebelum' => $alat->kondisi,
            'kondisi_sesudah' => null,
            'catatan' => null,
        ];
    }
}
