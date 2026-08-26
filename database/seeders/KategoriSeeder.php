<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Excavator',
                'deskripsi' => 'Alat berat untuk penggalian dan pengangkutan material tanah.',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Bulldozer',
                'deskripsi' => 'Alat berat untuk mendorong, meratakan, dan mengangkut material.',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Concrete Equipment',
                'deskripsi' => 'Peralatan untuk pencetakan, pengecoran, dan pemrosesan beton.',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Generator',
                'deskripsi' => 'Mesin pembangkit listrik untuk kebutuhan proyek konstruksi.',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Compressor',
                'deskripsi' => 'Kompresor udara untuk peralatan pneumatik dan pengecatan.',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Lifting Equipment',
                'deskripsi' => 'Peralatan pengangkat dan pengangkutan beban berat.',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Scaffolding',
                'deskripsi' => 'Perancah untuk akses kerja di ketinggian.',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Road Equipment',
                'deskripsi' => 'Peralatan untuk konstruksi dan pemeliharaan jalan.',
                'status' => 'aktif',
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
