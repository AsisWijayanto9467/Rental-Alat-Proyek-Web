<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        $totalPenyewaan = Penyewaan::where('user_id', $userId)->count();
        $pendingCount = Penyewaan::where('user_id', $userId)->where('status', 'pending')->count();
        $activeCount = Penyewaan::where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'menunggu_pembayaran', 'dibayar', 'sedang_disewa'])
            ->count();
        $completedCount = Penyewaan::where('user_id', $userId)->where('status', 'selesai')->count();

        $recentPenyewaan = Penyewaan::with('detailPenyewaans.alat')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'totalPenyewaan', 'pendingCount', 'activeCount', 'completedCount', 'recentPenyewaan'
        ));
    }

    public function myRentals()
    {
        $penyewaans = Penyewaan::with('detailPenyewaans.alat')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.rentals', compact('penyewaans'));
    }

    public function rentalDetail($id)
    {
        $penyewaan = Penyewaan::with(['detailPenyewaans.alat', 'pembayarans', 'pengembalian', 'dendas'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        ActivityLogService::lihatTransaksi(auth()->id());

        return view('customer.rental-detail', compact('penyewaan'));
    }

    public function profile()
    {
        return view('customer.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only(['nama', 'email', 'no_telepon', 'alamat']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
