<?php

namespace Database\Seeders;

use App\Models\AlatProyek;
use App\Models\Denda;
use App\Models\DetailPenyewaan;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenyewaanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'budi@example.com')->first() ?? User::first();

        if (! $user) {
            return;
        }

        $alat = AlatProyek::first();

        if (! $alat) {
            return;
        }

        $harian = (float) $alat->harga_sewa_harian;
        $hari = 5;
        $subtotal = $harian * $hari;

        $this->seedPending($user, $alat, $hari, $subtotal);
        $this->seedMenungguPembayaran($user, $alat, $hari, $subtotal);
        $this->seedMenungguVerifikasi($user, $alat, $hari, $subtotal);
        $this->seedDibayar($user, $alat, $hari, $subtotal);
        $this->seedMenungguInspeksi($user, $alat, $hari, $subtotal);
        $this->seedSelesaiDenganDenda($user, $alat, $hari, $subtotal);
    }

    private function seedPending(User $user, AlatProyek $alat, int $hari, float $subtotal): void
    {
        $penyewaan = Penyewaan::create([
            'kode_penyewaan' => 'PW-PENDING-0001',
            'user_id' => $user->id,
            'tanggal_pengajuan' => now()->subDays(1),
            'tanggal_mulai' => now()->addDays(2),
            'tanggal_selesai' => now()->addDays(2 + $hari),
            'total_hari' => $hari,
            'subtotal' => $subtotal,
            'denda' => 0,
            'total' => $subtotal,
            'status' => 'pending',
            'catatan' => 'Contoh penyewaan menunggu persetujuan.',
        ]);

        $this->detail($penyewaan, $alat, $hari, $subtotal);
    }

    private function seedMenungguPembayaran(User $user, AlatProyek $alat, int $hari, float $subtotal): void
    {
        $penyewaan = Penyewaan::create([
            'kode_penyewaan' => 'PW-MENUNGGU-0001',
            'user_id' => $user->id,
            'tanggal_pengajuan' => now()->subDays(3),
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(1 + $hari),
            'total_hari' => $hari,
            'subtotal' => $subtotal,
            'denda' => 0,
            'total' => $subtotal,
            'status' => 'menunggu_pembayaran',
            'catatan' => 'Disetujui, segera lakukan pembayaran.',
        ]);

        $this->detail($penyewaan, $alat, $hari, $subtotal);
    }

    private function seedMenungguVerifikasi(User $user, AlatProyek $alat, int $hari, float $subtotal): void
    {
        $penyewaan = Penyewaan::create([
            'kode_penyewaan' => 'PW-VERIFIKASI-0001',
            'user_id' => $user->id,
            'tanggal_pengajuan' => now()->subDays(4),
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(1 + $hari),
            'total_hari' => $hari,
            'subtotal' => $subtotal,
            'denda' => 0,
            'total' => $subtotal,
            'status' => 'menunggu_pembayaran',
            'catatan' => 'Bukti pembayaran sudah diunggah, menunggu verifikasi.',
        ]);

        $this->detail($penyewaan, $alat, $hari, $subtotal);

        Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => 'PAY-VERIFIKASI-0001',
            'tanggal_pembayaran' => now()->subDay(),
            'jumlah' => $subtotal,
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'seed/bukti-contoh.jpg',
            'status' => 'pending',
            'catatan' => 'Menunggu verifikasi pembayaran.',
        ]);
    }

    private function seedDibayar(User $user, AlatProyek $alat, int $hari, float $subtotal): void
    {
        $penyewaan = Penyewaan::create([
            'kode_penyewaan' => 'PW-DIBAYAR-0001',
            'user_id' => $user->id,
            'tanggal_pengajuan' => now()->subDays(10),
            'tanggal_mulai' => now()->subDays(7),
            'tanggal_selesai' => now()->subDays(2),
            'total_hari' => $hari,
            'subtotal' => $subtotal,
            'denda' => 0,
            'total' => $subtotal,
            'status' => 'dibayar',
            'catatan' => 'Sedang disewa, siap diproses pengembalian.',
        ]);

        $this->detail($penyewaan, $alat, $hari, $subtotal);

        Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => 'PAY-DIBAYAR-0001',
            'tanggal_pembayaran' => now()->subDays(8),
            'jumlah' => $subtotal,
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'seed/bukti-contoh.jpg',
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now()->subDays(8),
            'catatan' => 'Pembayaran diverifikasi.',
        ]);
    }

    private function seedMenungguInspeksi(User $user, AlatProyek $alat, int $hari, float $subtotal): void
    {
        $penyewaan = Penyewaan::create([
            'kode_penyewaan' => 'PW-INSPEKSI-0001',
            'user_id' => $user->id,
            'tanggal_pengajuan' => now()->subDays(10),
            'tanggal_mulai' => now()->subDays(8),
            'tanggal_selesai' => now()->subDays(3),
            'total_hari' => $hari,
            'subtotal' => $subtotal,
            'denda' => 0,
            'total' => $subtotal,
            'status' => 'dibayar',
            'catatan' => 'Pengembalian sudah diajukan, menunggu inspeksi.',
        ]);

        $this->detail($penyewaan, $alat, $hari, $subtotal);

        Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => 'PAY-INSPEKSI-0001',
            'tanggal_pembayaran' => now()->subDays(9),
            'jumlah' => $subtotal,
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'seed/bukti-contoh.jpg',
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now()->subDays(9),
            'catatan' => 'Pembayaran diverifikasi.',
        ]);

        Pengembalian::create([
            'penyewaan_id' => $penyewaan->id,
            'tanggal_pengembalian' => now()->subDays(3),
            'kondisi_alat' => 'Alat kembali dalam kondisi baik.',
            'foto' => 'seed/foto-contoh.jpg',
            'terlambat_hari' => 0,
            'status' => 'menunggu_inspeksi',
            'catatan' => 'Menunggu inspeksi oleh petugas.',
        ]);
    }

    private function seedSelesaiDenganDenda(User $user, AlatProyek $alat, int $hari, float $subtotal): void
    {
        $denda = 500000;

        $penyewaan = Penyewaan::create([
            'kode_penyewaan' => 'PW-SELESAI-0001',
            'user_id' => $user->id,
            'tanggal_pengajuan' => now()->subDays(20),
            'tanggal_mulai' => now()->subDays(15),
            'tanggal_selesai' => now()->subDays(10),
            'total_hari' => $hari,
            'subtotal' => $subtotal,
            'denda' => $denda,
            'total' => $subtotal + $denda,
            'status' => 'selesai',
            'catatan' => 'Penyewaan selesai dengan denda keterlambatan.',
        ]);

        $this->detail($penyewaan, $alat, $hari, $subtotal);

        Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => 'PAY-SELESAI-0001',
            'tanggal_pembayaran' => now()->subDays(16),
            'jumlah' => $subtotal,
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'seed/bukti-contoh.jpg',
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now()->subDays(16),
            'catatan' => 'Pembayaran sewa diverifikasi.',
        ]);

        $pengembalian = Pengembalian::create([
            'penyewaan_id' => $penyewaan->id,
            'tanggal_pengembalian' => now()->subDays(10),
            'kondisi_alat' => 'Alat kembali dengan keterlambatan 2 hari.',
            'foto' => 'seed/foto-contoh.jpg',
            'terlambat_hari' => 2,
            'status' => 'diterima',
            'catatan' => 'Diterima dengan catatan keterlambatan.',
        ]);

        Denda::create([
            'penyewaan_id' => $penyewaan->id,
            'pengembalian_id' => $pengembalian->id,
            'jenis_denda' => 'terlambat',
            'jumlah' => $denda,
            'alasan' => 'Keterlambatan pengembalian 2 hari.',
            'status' => 'pending',
        ]);
    }

    private function detail(Penyewaan $penyewaan, AlatProyek $alat, int $hari, float $subtotal): void
    {
        DetailPenyewaan::create([
            'penyewaan_id' => $penyewaan->id,
            'alat_id' => $alat->id,
            'jumlah' => 1,
            'harga_sewa' => (float) $alat->harga_sewa_harian,
            'subtotal' => $subtotal,
            'kondisi_sebelum' => 'baik',
            'catatan' => "Sewa selama {$hari} hari.",
        ]);
    }
}
