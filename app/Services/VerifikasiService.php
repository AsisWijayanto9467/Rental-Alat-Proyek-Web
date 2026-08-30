<?php

namespace App\Services;

use App\Models\Denda;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\DB;

/**
 * Menyimpan seluruh logika verifikasi yang dilakukan Admin/Petugas.
 *
 * Service ini menjadi sumber kebenaran (source of truth) untuk transisi status
 * pada alur user:
 *
 *   pending -> menunggu_pembayaran / ditolak
 *   menunggu_pembayaran (pembayaran pending) -> penyewaan dibayar
 *   menunggu_inspeksi -> diterima (selesai) / ditolak (+ denda otomatis)
 *
 * UI Admin/Petugas DI LUAR scope project ini tinggal memanggil method di sini.
 */
class VerifikasiService
{
    /**
     * Menyetujui pengajuan penyewaan.
     */
    public function setujuiPenyewaan(int $penyewaanId, int $processedBy): Penyewaan
    {
        /** @var Penyewaan $penyewaan */
        $penyewaan = Penyewaan::findOrFail($penyewaanId);

        if ($penyewaan->status !== 'pending') {
            return $penyewaan;
        }

        DB::transaction(function () use ($penyewaan, $processedBy): void {
            $penyewaan->update([
                'status' => 'menunggu_pembayaran',
                'processed_by' => $processedBy,
            ]);

            ActivityLogService::setujuiPenyewaan($processedBy, $penyewaan->id);
        });

        return $penyewaan;
    }

    /**
     * Menolak pengajuan penyewaan. Stok yang sudah dipesan otomatis dikembalikan.
     */
    public function tolakPenyewaan(int $penyewaanId, int $processedBy, ?string $alasan = null): Penyewaan
    {
        /** @var Penyewaan $penyewaan */
        $penyewaan = Penyewaan::with('detailPenyewaans.alat')->findOrFail($penyewaanId);

        if ($penyewaan->status !== 'pending') {
            return $penyewaan;
        }

        DB::transaction(function () use ($penyewaan, $processedBy, $alasan): void {
            $penyewaan->update([
                'status' => 'ditolak',
                'processed_by' => $processedBy,
                'alasan_penolakan' => $alasan,
            ]);

            $this->pulihkanStok($penyewaan);

            ActivityLogService::tolakPenyewaan($processedBy, $penyewaan->id);
        });

        return $penyewaan;
    }

    /**
     * Memverifikasi pembayaran setelah bukti diperiksa Admin/Petugas.
     *
     * Jika pembayaran untuk denda -> denda menjadi dibayar.
     * Jika pembayaran untuk sewa   -> penyewaan menjadi dibayar.
     */
    public function verifikasiPembayaran(int $pembayaranId, int $verifikasiOleh, ?string $catatan = null): Pembayaran
    {
        /** @var Pembayaran $pembayaran */
        $pembayaran = Pembayaran::with('penyewaan')->findOrFail($pembayaranId);

        if ($pembayaran->status !== 'pending') {
            return $pembayaran;
        }

        DB::transaction(function () use ($pembayaran, $verifikasiOleh, $catatan): void {
            $pembayaran->update([
                'status' => 'diverifikasi',
                'diverifikasi_oleh' => $verifikasiOleh,
                'tanggal_verifikasi' => now(),
                'catatan' => $catatan,
            ]);

            if ($pembayaran->denda_id) {
                $denda = Denda::find($pembayaran->denda_id);
                if ($denda && $denda->status === 'pending') {
                    $denda->update(['status' => 'dibayar']);
                    ActivityLogService::bayarDenda($verifikasiOleh, $denda->id);
                }
            } else {
                $penyewaan = $pembayaran->penyewaan;
                if ($penyewaan) {
                    $penyewaan->update(['status' => 'dibayar']);
                }
            }

            ActivityLogService::verifikasiPembayaran($verifikasiOleh, $pembayaran->id);
        });

        return $pembayaran;
    }

    /**
     * Menolak pembayaran / bukti tidak valid. Penyewaan tetap menunggu_pembayaran.
     */
    public function tolakPembayaran(int $pembayaranId, int $verifikasiOleh, ?string $alasan = null): Pembayaran
    {
        /** @var Pembayaran $pembayaran */
        $pembayaran = Pembayaran::findOrFail($pembayaranId);

        if ($pembayaran->status !== 'pending') {
            return $pembayaran;
        }

        DB::transaction(function () use ($pembayaran, $verifikasiOleh, $alasan): void {
            $pembayaran->update([
                'status' => 'ditolak',
                'diverifikasi_oleh' => $verifikasiOleh,
                'tanggal_verifikasi' => now(),
                'catatan' => $alasan,
            ]);

            ActivityLogService::tolakPembayaran($verifikasiOleh, $pembayaran->id);
        });

        return $pembayaran;
    }

    /**
     * Menerima hasil inspeksi pengembalian. Alat sesuai, tidak ada denda.
     * Penyewaan dianggap selesai dan stok dikembalikan.
     */
    public function terimaPengembalian(int $pengembalianId, int $diterimaOleh, ?string $catatan = null): Pengembalian
    {
        /** @var Pengembalian $pengembalian */
        $pengembalian = Pengembalian::with('penyewaan.detailPenyewaans.alat')->findOrFail($pengembalianId);

        if ($pengembalian->status === 'diterima') {
            return $pengembalian;
        }

        if ($pengembalian->status !== 'menunggu_inspeksi') {
            abort(422, 'Pengembalian tidak dalam kondisi menunggu inspeksi.');
        }

        DB::transaction(function () use ($pengembalian, $diterimaOleh, $catatan): void {
            $pengembalian->update([
                'status' => 'diterima',
                'diterima_oleh' => $diterimaOleh,
                'catatan' => $catatan,
            ]);

            $penyewaan = $pengembalian->penyewaan;
            $penyewaan->update(['status' => 'selesai']);

            $this->pulihkanStok($penyewaan);

            ActivityLogService::prosesPengembalian($diterimaOleh, $pengembalian->id);
        });

        return $pengembalian;
    }

    /**
     * Menolak hasil inspeksi pengembalian. Sistem OTOMATIS membuat denda
     * (status pending) dan memastikan tidak ada denda duplikat.
     */
    public function tolakPengembalian(
        int $pengembalianId,
        int $diterimaOleh,
        float $jumlah,
        string $alasan,
        string $jenisDenda = 'kerusakan'
    ): Pengembalian {
        /** @var Pengembalian $pengembalian */
        $pengembalian = Pengembalian::with('penyewaan.detailPenyewaans.alat')->findOrFail($pengembalianId);

        if ($pengembalian->status === 'ditolak') {
            return $pengembalian;
        }

        if ($pengembalian->status !== 'menunggu_inspeksi') {
            abort(422, 'Pengembalian tidak dalam kondisi menunggu inspeksi.');
        }

        $penyewaan = $pengembalian->penyewaan;

        DB::transaction(function () use ($pengembalian, $penyewaan, $diterimaOleh, $jumlah, $alasan, $jenisDenda): void {
            $pengembalian->update([
                'status' => 'ditolak',
                'diterima_oleh' => $diterimaOleh,
            ]);

            $this->buatDendaJikaBelumAda($pengembalian, $penyewaan, $jenisDenda, $jumlah, $alasan, $diterimaOleh);

            ActivityLogService::prosesPengembalian($diterimaOleh, $pengembalian->id);
        });

        return $pengembalian;
    }

    /**
     * Membuat denda hanya jika belum ada denda untuk pengembalian tersebut
     * (mencegah denda duplikat ketika proses dijalankan lebih dari sekali).
     */
    private function buatDendaJikaBelumAda(
        Pengembalian $pengembalian,
        Penyewaan $penyewaan,
        string $jenisDenda,
        float $jumlah,
        string $alasan,
        int $dibuatOleh
    ): void {
        $sudahAda = Denda::query()
            ->where('penyewaan_id', $penyewaan->id)
            ->where('pengembalian_id', $pengembalian->id)
            ->exists();

        if ($sudahAda) {
            return;
        }

        $denda = Denda::create([
            'penyewaan_id' => $penyewaan->id,
            'pengembalian_id' => $pengembalian->id,
            'jenis_denda' => $jenisDenda,
            'jumlah' => $jumlah,
            'alasan' => $alasan,
            'status' => 'pending',
        ]);

        $penyewaan->increment('denda', $jumlah);
        $penyewaan->increment('total', $jumlah);

        ActivityLogService::catatDenda($dibuatOleh, $denda->id);
    }

    /**
     * Mengembalikan stok alat yang dipesan (dipakai saat penyewaan dibatalkan,
     * ditolak, atau pengembalian diterima sehingga penyewaan selesai).
     */
    private function pulihkanStok(Penyewaan $penyewaan): void
    {
        foreach ($penyewaan->detailPenyewaans as $detail) {
            $alat = $detail->alat;

            if (! $alat) {
                continue;
            }

            $alat->increment('stok_tersedia', $detail->jumlah);
        }
    }
}
