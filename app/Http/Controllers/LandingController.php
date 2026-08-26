<?php

namespace App\Http\Controllers;

use App\Models\AlatProyek;
use App\Models\Kategori;

class LandingController extends Controller
{
    public function index()
    {
        $featuredAlat = AlatProyek::with(['kategori', 'lokasi'])
            ->where('status', 'tersedia')
            ->where('stok_tersedia', '>', 0)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $kategoris = Kategori::where('status', 'aktif')
            ->withCount(['alatProyeks as jumlah_alat' => function ($query) {
                $query->where('status', 'tersedia')->where('stok_tersedia', '>', 0);
            }])
            ->get();

        return view('pages.landing', compact('featuredAlat', 'kategoris'));
    }
}
