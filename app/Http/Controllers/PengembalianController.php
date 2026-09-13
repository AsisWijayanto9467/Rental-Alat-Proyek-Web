<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengembalianRequest;
use App\Models\Pengembalian;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengembalianController extends Controller
{
    /**
     * Halaman pengembalian. Hanya boleh dibuka ketika penyewaan sudah dibayar
     * (atau sedang disewa) dan belum ada pengajuan pengembalian sebelumnya.
     */
    public function create($id)
    {
        $penyewaan = $this->ownPenyewaan($id)->load('user');

        if (! in_array($penyewaan->status, ['dibayar', 'sedang_disewa'])) {
            return redirect()->route('customer.rental-detail', $penyewaan->id)
                ->with('error', 'Penyewaan belum siap untuk dikembalikan.');
        }

        if ($penyewaan->pengembalian) {
            return redirect()->route('customer.rental-detail', $penyewaan->id)
                ->with('error', 'Pengembalian untuk penyewaan ini sudah diajukan.');
        }

        return view('customer.pengembalian', compact('penyewaan'));
    }

    public function store(PengembalianRequest $request, $id)
    {
        $penyewaan = $this->ownPenyewaan($id);

        if (! in_array($penyewaan->status, ['dibayar', 'sedang_disewa'])) {
            return back()->with('error', 'Penyewaan belum siap untuk dikembalikan.');
        }

        if ($penyewaan->pengembalian) {
            return back()->with('error', 'Pengembalian untuk penyewaan ini sudah diajukan.');
        }

        $tanggalPengembalian = Carbon::parse($request->tanggal_pengembalian);
        $tanggalSelesai = Carbon::parse($penyewaan->tanggal_selesai);
        $terlambatHari = max(0, $tanggalSelesai->startOfDay()->diffInDays($tanggalPengembalian->startOfDay(), false));

        try {
            $fotoPath = $request->file('foto')->store('dokumen', 'cross');

            // User mengajukan pengembalian -> status 'menunggu_inspeksi'.
            // Tidak langsung 'diterima'/'selesai'; fotonya akan diperiksa
            // Admin/Petugas. `diterima_oleh` diisi petugas saat inspeksi.
            DB::transaction(function () use ($request, $penyewaan, $fotoPath, $tanggalPengembalian, $terlambatHari): void {
                $pengembalian = Pengembalian::create([
                    'penyewaan_id' => $penyewaan->id,
                    'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
                    'diterima_oleh' => null,
                    'kondisi_alat' => $request->kondisi_alat,
                    'foto' => $fotoPath,
                    'terlambat_hari' => $terlambatHari,
                    'status' => 'menunggu_inspeksi',
                    'catatan' => $request->catatan,
                ]);

                ActivityLogService::prosesPengembalian(auth()->id(), $pengembalian->id);
            });

            return redirect()->route('customer.rental-detail', $penyewaan->id)
                ->with('success', 'Pengembalian berhasil diajukan! Menunggu inspeksi.');
        } catch (\Exception $e) {
            Log::error('Gagal mengajukan pengembalian', ['penyewaan_id' => $penyewaan->id, 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
