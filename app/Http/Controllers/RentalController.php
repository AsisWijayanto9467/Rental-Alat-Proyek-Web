<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenyewaanRequest;
use App\Models\AlatProyek;
use App\Models\DetailPenyewaan;
use App\Models\Penyewaan;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function create($id)
    {
        $alat = AlatProyek::with(['kategori', 'lokasi'])->findOrFail($id);

        if ($alat->status !== 'tersedia' || $alat->stok_tersedia <= 0) {
            return redirect()->route('equipment.show', $id)
                ->with('error', 'Alat ini sedang tidak tersedia untuk disewa.');
        }

        return view('customer.rental-form', compact('alat'));
    }

    public function store(PenyewaanRequest $request)
    {
        $alat = AlatProyek::findOrFail($request->alat_id);

        if ($alat->status !== 'tersedia' || $alat->stok_tersedia <= 0) {
            return back()->with('error', 'Alat ini sedang tidak tersedia.');
        }

        if ($request->jumlah > $alat->stok_tersedia) {
            return back()->withInput()
                ->with('error', "Stok tersedia hanya {$alat->stok_tersedia} unit.");
        }

        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        $totalHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

        if ($totalHari < 1) {
            return back()->withInput()->with('error', 'Tanggal tidak valid.');
        }

        $subtotal = $alat->harga_sewa_harian * $request->jumlah * $totalHari;

        DB::beginTransaction();

        try {
            // Penyewaan baru selalu berstatus 'pending' (menunggu persetujuan
            // Admin/Petugas). Tidak langsung disetujui maupun dibayar.
            $penyewaan = Penyewaan::create([
                'kode_penyewaan' => (new Penyewaan)->generateKode(),
                'user_id' => auth()->id(),
                'tanggal_pengajuan' => now()->toDateString(),
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'total_hari' => $totalHari,
                'subtotal' => $subtotal,
                'denda' => 0,
                'total' => $subtotal,
                'status' => 'pending',
                'catatan' => $request->catatan,
            ]);

            DetailPenyewaan::create([
                'penyewaan_id' => $penyewaan->id,
                'alat_id' => $alat->id,
                'jumlah' => $request->jumlah,
                'harga_sewa' => $alat->harga_sewa_harian,
                'subtotal' => $subtotal,
                'kondisi_sebelum' => $alat->kondisi,
                'catatan' => null,
            ]);

            // Stok dipesan (dikurangi) saat pengajuan, dikembalikan kembali jika
            // penyewaan ditolak, dibatalkan, atau selesai.
            $alat->decrement('stok_tersedia', $request->jumlah);

            ActivityLogService::ajukanPenyewaan(auth()->id(), $penyewaan->id);

            DB::commit();

            return redirect()->route('customer.rental-detail', $penyewaan->id)
                ->with('success', 'Penyewaan berhasil diajukan! Menunggu persetujuan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function batal($id)
    {
        $penyewaan = $this->ownPenyewaan($id)->load('detailPenyewaans.alat');

        if ($penyewaan->status !== 'pending') {
            return back()->with('error', 'Penyewaan ini tidak dapat dibatalkan pada status saat ini.');
        }

        DB::beginTransaction();

        try {
            $penyewaan->update(['status' => 'dibatalkan']);

            foreach ($penyewaan->detailPenyewaans as $detail) {
                $detail->alat()->increment('stok_tersedia', $detail->jumlah);
            }

            ActivityLogService::batalkanPenyewaan(auth()->id(), $penyewaan->id);

            DB::commit();

            return redirect()->route('customer.rental-detail', $penyewaan->id)
                ->with('success', 'Penyewaan berhasil dibatalkan & stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
