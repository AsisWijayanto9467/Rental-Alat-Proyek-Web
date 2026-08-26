<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $lokasis = [
            [
                'nama_lokasi' => 'Gudang Pusat Jakarta',
                'alamat' => 'Jl. Pusat Gudang No. 1, Kebayoran Baru, Jakarta Selatan',
                'keterangan' => 'Gudang utama penyimpanan seluruh alat berat dan peralatan proyek.',
                'status' => 'aktif',
            ],
            [
                'nama_lokasi' => 'Depo Bandung',
                'alamat' => 'Jl. Raya Bandung Timur KM 5, Cileunyi, Bandung',
                'keterangan' => 'Depo penyimpanan alat untuk proyek di wilayah Jawa Barat.',
                'status' => 'aktif',
            ],
            [
                'nama_lokasi' => 'Gudang Surabaya',
                'alamat' => 'Jl. Raya Surabaya-Gresik KM 10, Gresik, Jawa Timur',
                'keterangan' => 'Gudang regional untuk melayani proyek di Jawa Timur.',
                'status' => 'aktif',
            ],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}
