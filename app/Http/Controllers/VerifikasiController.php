<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use App\Services\VerifikasiService;
use Illuminate\Http\Request;

/**
 * Endpoint verifikasi untuk Admin/Petugas.
 *
 * NOTE: UI lengkap dashboard Admin/Petugas belum dibuat pada project ini.
 * Controller ini menyediakan backend yang siap dipakai UI Admin/Petugas
 * (produk terpisah). Endpoint ini hanya boleh diakses peran admin & petugas
 * (lihat route 'role:admin,petugas').
 */
class VerifikasiController extends Controller
{
    public function __construct(private readonly VerifikasiService $verifikasi) {}

    /**
     * Halaman ringkas (opsional) untuk memproses antrean verifikasi.
     */
    public function dashboard()
    {
        $penyewaans = Penyewaan::with(['user', 'detailPenyewaans.alat'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pembayarans = Pembayaran::with(['penyewaan.user', 'denda'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pengembalians = Pengembalian::with(['penyewaan.user'])
            ->where('status', 'menunggu_inspeksi')
            ->latest()
            ->get();

        return view('verifikasi.dashboard', compact('penyewaans', 'pembayarans', 'pengembalians'));
    }

    public function setujuiPenyewaan(Request $request, int $id)
    {
        $penyewaan = $this->verifikasi->setujuiPenyewaan($id, auth()->id());

        return back()->with('success', "Penyewaan {$penyewaan->kode_penyewaan} disetujui. Menunggu pembayaran.");
    }

    public function tolakPenyewaan(Request $request, int $id)
    {
        $data = $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $penyewaan = $this->verifikasi->tolakPenyewaan($id, auth()->id(), $data['alasan_penolakan']);

        return back()->with('success', "Penyewaan {$penyewaan->kode_penyewaan} ditolak.");
    }

    public function verifikasiPembayaran(Request $request, int $id)
    {
        $pembayaran = $this->verifikasi->verifikasiPembayaran($id, auth()->id());

        return back()->with('success', "Pembayaran {$pembayaran->kode_pembayaran} berhasil diverifikasi.");
    }

    public function tolakPembayaran(Request $request, int $id)
    {
        $data = $request->validate([
            'alasan' => 'required|string|max:1000',
        ]);

        $pembayaran = $this->verifikasi->tolakPembayaran($id, auth()->id(), $data['alasan']);

        return back()->with('success', "Pembayaran {$pembayaran->kode_pembayaran} ditolak.");
    }

    public function terimaPengembalian(Request $request, int $id)
    {
        $pengembalian = $this->verifikasi->terimaPengembalian($id, auth()->id());

        return back()->with('success', 'Pengembalian diterima. Penyewaan selesai, tanpa denda.');
    }

    public function tolakPengembalian(Request $request, int $id)
    {
        $data = $request->validate([
            'jenis_denda' => 'required|in:terlambat,kerusakan,kehilangan',
            'jumlah' => 'required|numeric|min:1',
            'alasan' => 'required|string|max:1000',
        ]);

        $pengembalian = $this->verifikasi->tolakPengembalian(
            $id,
            auth()->id(),
            (float) $data['jumlah'],
            $data['alasan'],
            $data['jenis_denda'],
        );

        return back()->with('success', 'Pengembalian ditolak. Denda otomatis telah dibuat.');
    }
}
