<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penyewaan;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function show($id)
    {
        $penyewaan = Penyewaan::with(['detailPenyewaans.alat', 'pembayarans'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (!in_array($penyewaan->status, ['menunggu_pembayaran', 'disetujui'])) {
            return redirect()->route('customer.rental-detail', $id)
                ->with('error', 'Penyewaan ini tidak bisa melakukan pembayaran saat ini.');
        }

        return view('customer.payment', compact('penyewaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'bukti_pembayaran' => 'required|image|max:2048',
            'catatan' => 'nullable|string|max:500',
        ]);

        $penyewaan = Penyewaan::where('user_id', auth()->id())->findOrFail($request->penyewaan_id);

        if (!in_array($penyewaan->status, ['menunggu_pembayaran', 'disetujui'])) {
            return back()->with('error', 'Penyewaan ini tidak bisa melakukan pembayaran saat ini.');
        }

        $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        $pembayaran = Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => (new Pembayaran)->generateKode(),
            'tanggal_pembayaran' => now()->toDateString(),
            'jumlah' => $penyewaan->total,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $buktiPath,
            'status' => 'pending',
            'catatan' => $request->catatan,
        ]);

        $penyewaan->update(['status' => 'menunggu_pembayaran']);

        ActivityLogService::uploadPembayaran(auth()->id(), $pembayaran->id);

        return redirect()->route('customer.rental-detail', $penyewaan->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi.');
    }
}
