<?php

namespace App\Http\Controllers;

use App\Models\AlatProyek;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = AlatProyek::with(['kategori', 'lokasi'])
            ->where('status', 'tersedia')
            ->where('stok_tersedia', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_alat', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('kode_alat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', $request->kategori);
            });
        }

        if ($request->filled('lokasi')) {
            $query->whereHas('lokasi', function ($q) use ($request) {
                $q->where('nama_lokasi', $request->lokasi);
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('harga_sewa_harian', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('harga_sewa_harian', 'desc');
                    break;
                case 'name':
                    $query->orderBy('nama_alat', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $alats = $query->paginate(12)->withQueryString();
        $kategoris = Kategori::where('status', 'aktif')->get();
        $lokasis = Lokasi::where('status', 'aktif')->get();

        return view('pages.equipment', compact('alats', 'kategoris', 'lokasis'));
    }

    public function show($id)
    {
        $alat = AlatProyek::with(['kategori', 'lokasi'])->findOrFail($id);

        $relatedAlat = AlatProyek::with(['kategori', 'lokasi'])
            ->where('kategori_id', $alat->kategori_id)
            ->where('id', '!=', $alat->id)
            ->where('status', 'tersedia')
            ->where('stok_tersedia', '>', 0)
            ->take(4)
            ->get();

        return view('pages.equipment-detail', compact('alat', 'relatedAlat'));
    }
}
