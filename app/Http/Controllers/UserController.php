<?php

namespace App\Http\Controllers;

use App\Models\AlatProyek;
use App\Models\Penyewaan;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        $totalPenyewaan = Penyewaan::where('user_id', $userId)->count();
        $penyewaanAktif = Penyewaan::where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'menunggu_pembayaran', 'dibayar', 'sedang_disewa'])
            ->count();
        $totalAlat = AlatProyek::where('status', 'tersedia')->count();

        $recentPenyewaan = Penyewaan::where('user_id', $userId)
            ->with('detailPenyewaans.alat')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalPenyewaan',
            'penyewaanAktif',
            'totalAlat',
            'recentPenyewaan'
        ));
    }
}
