<?php

namespace Database\Seeders;

use App\Models\AlatProyek;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class AlatProyekSeeder extends Seeder
{
    public function run(): void
    {
        $excavator = Kategori::where('nama_kategori', 'Excavator')->first();
        $bulldozer = Kategori::where('nama_kategori', 'Bulldozer')->first();
        $concrete = Kategori::where('nama_kategori', 'Concrete Equipment')->first();
        $generator = Kategori::where('nama_kategori', 'Generator')->first();
        $compressor = Kategori::where('nama_kategori', 'Compressor')->first();
        $lifting = Kategori::where('nama_kategori', 'Lifting Equipment')->first();
        $road = Kategori::where('nama_kategori', 'Road Equipment')->first();

        $jakarta = Lokasi::where('nama_lokasi', 'Gudang Pusat Jakarta')->first();
        $bandung = Lokasi::where('nama_lokasi', 'Depo Bandung')->first();
        $surabaya = Lokasi::where('nama_lokasi', 'Gudang Surabaya')->first();

        $alats = [
            [
                'kategori_id' => $excavator->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'EXC-001',
                'nama_alat' => 'Excavator Caterpillar 320',
                'deskripsi' => 'Excavator 20 ton, cocok untuk penggalian dan pengerukan proyek besar.',
                'harga_sewa_harian' => 3500000,
                'stok' => 3,
                'stok_tersedia' => 3,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $excavator->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'EXC-002',
                'nama_alat' => 'Mini Excavator Kubota U-50',
                'deskripsi' => 'Mini excavator 5 ton, ideal untuk area sempit dan pekerjaan ringan.',
                'harga_sewa_harian' => 1500000,
                'stok' => 5,
                'stok_tersedia' => 5,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $excavator->id,
                'lokasi_id' => $bandung->id,
                'kode_alat' => 'EXC-003',
                'nama_alat' => 'Excavator Komatsu PC200',
                'deskripsi' => 'Excavator 20 ton, performa tinggi untuk proyek infrastruktur.',
                'harga_sewa_harian' => 3200000,
                'stok' => 2,
                'stok_tersedia' => 2,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $bulldozer->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'BLD-001',
                'nama_alat' => 'Bulldozer Caterpillar D6',
                'deskripsi' => 'Bulldozer crawler untuk perataan tanah dan pendorongan material.',
                'harga_sewa_harian' => 4000000,
                'stok' => 2,
                'stok_tersedia' => 2,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $bulldozer->id,
                'lokasi_id' => $surabaya->id,
                'kode_alat' => 'BLD-002',
                'nama_alat' => 'Wheel Loader Komatsu WA-200',
                'deskripsi' => 'Wheel loader untuk pemuatan dan pengangkutan material.',
                'harga_sewa_harian' => 3000000,
                'stok' => 2,
                'stok_tersedia' => 2,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $concrete->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'CON-001',
                'nama_alat' => 'Concrete Mixer 350 Liter',
                'deskripsi' => 'Mesin pencampur beton kapasitas 350 liter untuk proyek menengah.',
                'harga_sewa_harian' => 500000,
                'stok' => 6,
                'stok_tersedia' => 6,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $concrete->id,
                'lokasi_id' => $bandung->id,
                'kode_alat' => 'CON-002',
                'nama_alat' => 'Concrete Vibrator',
                'deskripsi' => 'Mesin getar beton untuk menghilangkan gelembung udara pada pengecoran.',
                'harga_sewa_harian' => 300000,
                'stok' => 4,
                'stok_tersedia' => 4,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $generator->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'GEN-001',
                'nama_alat' => 'Generator 50 KVA',
                'deskripsi' => 'Generator diesel 50 KVA untuk pasokan listrik proyek.',
                'harga_sewa_harian' => 800000,
                'stok' => 4,
                'stok_tersedia' => 4,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $generator->id,
                'lokasi_id' => $surabaya->id,
                'kode_alat' => 'GEN-002',
                'nama_alat' => 'Generator 100 KVA',
                'deskripsi' => 'Generator diesel 100 KVA untuk proyek berskala besar.',
                'harga_sewa_harian' => 1500000,
                'stok' => 2,
                'stok_tersedia' => 2,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $compressor->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'CMP-001',
                'nama_alat' => 'Compressor 150 CFM',
                'deskripsi' => 'Kompresor portable untuk peralatan pneumatik dan sandblasting.',
                'harga_sewa_harian' => 400000,
                'stok' => 5,
                'stok_tersedia' => 5,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $compressor->id,
                'lokasi_id' => $bandung->id,
                'kode_alat' => 'CMP-002',
                'nama_alat' => 'Compressor 300 CFM',
                'deskripsi' => 'Kompresor besar untuk pekerjaan industrial dan konstruksi.',
                'harga_sewa_harian' => 700000,
                'stok' => 3,
                'stok_tersedia' => 3,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $lifting->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'LFT-001',
                'nama_alat' => 'Forklift 3 Ton',
                'deskripsi' => 'Forklift bertenaga diesel untuk pengangkutan material di gudang dan proyek.',
                'harga_sewa_harian' => 1200000,
                'stok' => 3,
                'stok_tersedia' => 3,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $lifting->id,
                'lokasi_id' => $surabaya->id,
                'kode_alat' => 'LFT-002',
                'nama_alat' => 'Mobile Crane 25 Ton',
                'deskripsi' => 'Crane mobile untuk pengangkutan beban berat di lokasi proyek.',
                'harga_sewa_harian' => 5000000,
                'stok' => 1,
                'stok_tersedia' => 1,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $road->id,
                'lokasi_id' => $jakarta->id,
                'kode_alat' => 'RD-001',
                'nama_alat' => 'Roller Compactor 10 Ton',
                'deskripsi' => 'Alat pemadat tanah dan aspal untuk proyek jalan.',
                'harga_sewa_harian' => 2500000,
                'stok' => 2,
                'stok_tersedia' => 2,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $road->id,
                'lokasi_id' => $bandung->id,
                'kode_alat' => 'RD-002',
                'nama_alat' => 'Asphalt Finisher',
                'deskripsi' => 'Mesin perata aspal untuk pembangunan dan perbaikan jalan.',
                'harga_sewa_harian' => 4500000,
                'stok' => 1,
                'stok_tersedia' => 1,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
            [
                'kategori_id' => $excavator->id,
                'lokasi_id' => $surabaya->id,
                'kode_alat' => 'EXC-004',
                'nama_alat' => 'Long Arm Excavator',
                'deskripsi' => 'Excavator dengan boom panjang untuk penggalian kedalaman.',
                'harga_sewa_harian' => 4500000,
                'stok' => 1,
                'stok_tersedia' => 1,
                'kondisi' => 'baik',
                'status' => 'tersedia',
            ],
        ];

        foreach ($alats as $alat) {
            AlatProyek::create($alat);
        }
    }
}
