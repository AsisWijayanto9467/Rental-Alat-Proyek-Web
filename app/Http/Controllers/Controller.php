<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Mengambil penyewaan milik user yang sedang login.
     *
     * - Data tidak ditemukan  -> 404
     * - Bukan milik user      -> 403 (mencegah akses penyewaan milik user lain)
     */
    protected function ownPenyewaan(int $id): Penyewaan
    {
        $penyewaan = Penyewaan::with(['detailPenyewaans.alat'])->findOrFail($id);

        abort_unless($penyewaan->user_id === auth()->id(), 403, 'Anda tidak memiliki akses ke penyewaan ini.');

        return $penyewaan;
    }
}
