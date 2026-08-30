<?php

namespace App\Http\Controllers;

use App\Http\Requests\PembayaranRequest;
use App\Models\Denda;
use App\Models\Pembayaran;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show($id)
    {
        $penyewaan = $this->ownPenyewaan($id)->load('pembayarans');

        // Pembayaran hanya boleh dilakukan ketika pengajuan sudah disetujui
        // oleh Admin/Petugas, yaitu pada status menunggu_pembayaran.
        if ($penyewaan->status !== 'menunggu_pembayaran') {
            return redirect()->route('customer.rental-detail', $id)
                ->with('error', 'Penyewaan ini tidak bisa melakukan pembayaran saat ini.');
        }

        $punyaPembayaranPending = $penyewaan->pembayarans->contains(
            fn ($p) => $p->status === 'pending'
        );

        if ($punyaPembayaranPending) {
            return redirect()->route('customer.rental-detail', $id)
                ->with('error', 'Bukti pembayaran sudah dikirim dan menunggu verifikasi. Silakan tunggu.');
        }

        return view('customer.payment', compact('penyewaan'));
    }

    public function showDenda($penyewaanId, $dendaId)
    {
        $penyewaan = $this->ownPenyewaan($penyewaanId)->load('pembayarans');

        $denda = Denda::where('penyewaan_id', $penyewaan->id)
            ->where('id', $dendaId)
            ->where('status', 'pending')
            ->firstOrFail();

        $punyaPembayaranPending = $penyewaan->pembayarans->contains(
            fn ($p) => $p->denda_id === $denda->id && $p->status === 'pending'
        );

        if ($punyaPembayaranPending) {
            return redirect()->route('customer.rental-detail', $penyewaanId)
                ->with('error', 'Bukti pembayaran denda sudah dikirim dan menunggu verifikasi.');
        }

        return view('customer.payment-denda', compact('penyewaan', 'denda'));
    }

    public function store(PembayaranRequest $request)
    {
        $penyewaan = $this->ownPenyewaan($request->penyewaan_id)->load('pembayarans');

        if ($request->denda_id) {
            $denda = Denda::where('penyewaan_id', $penyewaan->id)
                ->where('id', $request->denda_id)
                ->where('status', 'pending')
                ->firstOrFail();

            $punyaPembayaranPending = $penyewaan->pembayarans->contains(
                fn ($p) => $p->denda_id === $denda->id && $p->status === 'pending'
            );

            if ($punyaPembayaranPending) {
                return back()->with('error', 'Bukti pembayaran denda sudah dikirim dan menunggu verifikasi.');
            }

            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            $pembayaran = Pembayaran::create([
                'penyewaan_id' => $penyewaan->id,
                'denda_id' => $denda->id,
                'kode_pembayaran' => (new Pembayaran)->generateKode(),
                'tanggal_pembayaran' => now()->toDateString(),
                'jumlah' => $denda->jumlah,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
                'status' => 'pending',
                'catatan' => $request->catatan,
            ]);

            ActivityLogService::uploadPembayaran(auth()->id(), $pembayaran->id);

            return redirect()->route('customer.rental-detail', $penyewaan->id)
                ->with('success', 'Bukti pembayaran denda berhasil diunggah! Menunggu verifikasi.');
        }

        if ($penyewaan->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Penyewaan ini tidak bisa melakukan pembayaran saat ini.');
        }

        $punyaPembayaranPending = $penyewaan->pembayarans->contains(
            fn ($p) => $p->status === 'pending'
        );

        if ($punyaPembayaranPending) {
            return back()->with('error', 'Bukti pembayaran sudah dikirim dan menunggu verifikasi.');
        }

        $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        // Upload bukti pembayaran TIDAK langsung mengubah penyewaan menjadi dibayar.
        // Pembayaran disimpan status 'pending' (menunggu verifikasi) dan penyewaan
        // tetap 'menunggu_pembayaran' sampai Admin/Petugas memverifikasi.
        DB::transaction(function () use ($request, $penyewaan, $buktiPath): void {
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

            ActivityLogService::uploadPembayaran(auth()->id(), $pembayaran->id);
        });

        return redirect()->route('customer.rental-detail', $penyewaan->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi.');
    }
}
