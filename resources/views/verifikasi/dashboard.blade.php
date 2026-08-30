@extends('layouts.app')

@section('title', 'Verifikasi - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-[#2A2A2A]">Verifikasi</h1>
            <p class="text-gray-500 mt-1">Halaman backend ringkas Admin/Petugas untuk memproses antrean verifikasi. UI lengkap berada pada aplikasi admin terpisah.</p>
        </div>

        {{-- Pengajuan Penyewaan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-bold text-[#2A2A2A] mb-4">Pengajuan Penyewaan ({{ $penyewaans->count() }})</h3>
            @forelse($penyewaans as $pw)
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-[#F5F5F3] rounded-xl p-4 mb-3">
                <div>
                    <p class="font-bold text-[#2A2A2A] text-sm">{{ $pw->kode_penyewaan }} — {{ $pw->user?->nama }}</p>
                    <p class="text-gray-500 text-xs">{{ $pw->tanggal_mulai->format('d M Y') }} s/d {{ $pw->tanggal_selesai->format('d M Y') }} · Rp {{ number_format($pw->total, 0, ',', '.') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('verifikasi.penyewaan.setujui', $pw->id) }}">
                        @csrf
                        <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('verifikasi.penyewaan.tolak', $pw->id) }}" class="flex items-center gap-1">
                        @csrf
                        <input type="text" name="alasan_penolakan" placeholder="Alasan penolakan" required class="w-36 px-2 py-1.5 text-xs border border-gray-300 rounded-lg">
                        <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Tolak</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Tidak ada pengajuan menunggu.</p>
            @endforelse
        </div>

        {{-- Pembayaran --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-bold text-[#2A2A2A] mb-4">Pembayaran Menunggu Verifikasi ({{ $pembayarans->count() }})</h3>
            @forelse($pembayarans as $bayar)
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-[#F5F5F3] rounded-xl p-4 mb-3">
                <div>
                    <p class="font-bold text-[#2A2A2A] text-sm">{{ $bayar->kode_pembayaran }}</p>
                    <p class="text-gray-500 text-xs">{{ $bayar->penyewaan?->kode_penyewaan }} · {{ $bayar->penyewaan?->user?->nama }} · Rp {{ number_format($bayar->jumlah, 0, ',', '.') }} {{ $bayar->denda_id ? '· Denda' : '' }}</p>
                    @if($bayar->bukti_pembayaran)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($bayar->bukti_pembayaran) }}" alt="Bukti" class="mt-2 w-40 h-24 object-cover rounded-lg border border-gray-200">
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('verifikasi.pembayaran.verifikasi', $bayar->id) }}">
                        @csrf
                        <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Verifikasi</button>
                    </form>
                    <form method="POST" action="{{ route('verifikasi.pembayaran.tolak', $bayar->id) }}" class="flex items-center gap-1">
                        @csrf
                        <input type="text" name="alasan" placeholder="Alasan" required class="w-36 px-2 py-1.5 text-xs border border-gray-300 rounded-lg">
                        <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Tolak</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Tidak ada pembayaran menunggu.</p>
            @endforelse
        </div>

        {{-- Pengembalian --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-[#2A2A2A] mb-4">Pengembalian Menunggu Inspeksi ({{ $pengembalians->count() }})</h3>
            @forelse($pengembalians as $pmb)
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-[#F5F5F3] rounded-xl p-4 mb-3">
                <div>
                    <p class="font-bold text-[#2A2A2A] text-sm">{{ $pmb->penyewaan?->kode_penyewaan }} — {{ $pmb->penyewaan?->user?->nama }}</p>
                    <p class="text-gray-500 text-xs">Kembali {{ $pmb->tanggal_pengembalian->format('d M Y') }} · Terlambat {{ $pmb->terlambat_hari }} hari</p>
                    @if($pmb->foto)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($pmb->foto) }}" alt="Foto pengembalian" class="mt-2 w-40 h-24 object-cover rounded-lg border border-gray-200">
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('verifikasi.pengembalian.terima', $pmb->id) }}">
                        @csrf
                        <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Terima</button>
                    </form>
                    <form method="POST" action="{{ route('verifikasi.pengembalian.tolak', $pmb->id) }}" class="flex items-center gap-1">
                        @csrf
                        <select name="jenis_denda" required class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg">
                            <option value="terlambat">Terlambat</option>
                            <option value="kerusakan">Kerusakan</option>
                            <option value="kehilangan">Kehilangan</option>
                        </select>
                        <input type="number" name="jumlah" placeholder="Nominal" min="1" required class="w-28 px-2 py-1.5 text-xs border border-gray-300 rounded-lg">
                        <input type="text" name="alasan" placeholder="Alasan" required class="w-40 px-2 py-1.5 text-xs border border-gray-300 rounded-lg">
                        <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Tolak</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Tidak ada pengembalian menunggu.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection