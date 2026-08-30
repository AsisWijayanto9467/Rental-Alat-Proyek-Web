<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log(int $userId, string $aktivitas, ?string $modul = null, ?int $referensiId = null): void
    {
        ActivityLog::create([
            'user_id' => $userId,
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'referensi_id' => $referensiId,
            'ip_address' => Request::ip(),
            'created_at' => now(),
        ]);
    }

    public static function login(int $userId): void
    {
        self::log($userId, 'Login', 'Auth');
    }

    public static function logout(int $userId): void
    {
        self::log($userId, 'Logout', 'Auth');
    }

    public static function register(int $userId): void
    {
        self::log($userId, 'Register', 'Auth');
    }

    public static function lihatAlat(int $userId, int $alatId): void
    {
        self::log($userId, 'Melihat detail alat', 'AlatProyek', $alatId);
    }

    public static function ajukanPenyewaan(int $userId, int $penyewaanId): void
    {
        self::log($userId, 'Mengajukan penyewaan', 'Penyewaan', $penyewaanId);
    }

    public static function batalkanPenyewaan(int $userId, int $penyewaanId): void
    {
        self::log($userId, 'Membatalkan penyewaan', 'Penyewaan', $penyewaanId);
    }

    public static function uploadPembayaran(int $userId, int $pembayaranId): void
    {
        self::log($userId, 'Upload pembayaran', 'Pembayaran', $pembayaranId);
    }

    public static function lihatTransaksi(int $userId): void
    {
        self::log($userId, 'Melihat daftar transaksi', 'Penyewaan');
    }

    public static function setujuiPenyewaan(int $userId, int $penyewaanId): void
    {
        self::log($userId, 'Menyetujui penyewaan', 'Penyewaan', $penyewaanId);
    }

    public static function tolakPenyewaan(int $userId, int $penyewaanId): void
    {
        self::log($userId, 'Menolak penyewaan', 'Penyewaan', $penyewaanId);
    }

    public static function verifikasiPembayaran(int $userId, int $pembayaranId): void
    {
        self::log($userId, 'Memverifikasi pembayaran', 'Pembayaran', $pembayaranId);
    }

    public static function tolakPembayaran(int $userId, int $pembayaranId): void
    {
        self::log($userId, 'Menolak pembayaran', 'Pembayaran', $pembayaranId);
    }

    public static function prosesPengembalian(int $userId, int $pengembalianId): void
    {
        self::log($userId, 'Memproses pengembalian', 'Pengembalian', $pengembalianId);
    }

    public static function catatDenda(int $userId, int $dendaId): void
    {
        self::log($userId, 'Mencatat denda', 'Denda', $dendaId);
    }

    public static function bayarDenda(int $userId, int $dendaId): void
    {
        self::log($userId, 'Membayar denda', 'Denda', $dendaId);
    }
}
