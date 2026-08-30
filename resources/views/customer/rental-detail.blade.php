@extends('layouts.app')

@section('title', 'Rental Detail - RENTAL PRO')

@php
    $hasPendingPayment = $penyewaan->pembayarans->contains(fn ($p) => $p->status === 'pending');
    $statusPenyewaan = $penyewaan->status;
    $statusPengembalian = $penyewaan->pengembalian?->status;

    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'menunggu_pembayaran' => 'bg-orange-100 text-orange-800',
        'dibayar' => 'bg-blue-100 text-blue-800',
        'sedang_disewa' => 'bg-blue-100 text-blue-800',
        'menunggu_inspeksi' => 'bg-orange-100 text-orange-800',
        'selesai' => 'bg-gray-100 text-gray-600',
        'ditolak' => 'bg-red-100 text-red-800',
        'dibatalkan' => 'bg-red-100 text-red-800',
    ];
    $statusLabels = [
        'pending' => 'Menunggu Persetujuan',
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'dibayar' => 'Dibayar',
        'sedang_disewa' => 'Sedang Disewa',
        'menunggu_inspeksi' => 'Menunggu Inspeksi',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
        'dibatalkan' => 'Dibatalkan',
    ];

    $panel = null;
    if ($statusPenyewaan === 'pending') {
        $panel = [
            'icon' => 'fa-hourglass-half',
            'title' => 'Menunggu Persetujuan',
            'msg' => 'Pengajuan penyewaan sedang menunggu pemeriksaan. Silakan tunggu sampai pengajuan disetujui.',
            'tone' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        ];
    } elseif ($statusPenyewaan === 'ditolak') {
        $panel = [
            'icon' => 'fa-times-circle',
            'title' => 'Pengajuan Ditolak',
            'msg' => $penyewaan->alasan_penolakan
                ? 'Pengajuan penyewaan Anda ditolak. Alasan: '.$penyewaan->alasan_penolakan
                : 'Pengajuan penyewaan Anda ditolak dan tidak dapat dilanjutkan.',
            'tone' => 'bg-red-50 border-red-200 text-red-700',
        ];
    } elseif ($statusPenyewaan === 'menunggu_pembayaran' && $hasPendingPayment) {
        $panel = [
            'icon' => 'fa-clock',
            'title' => 'Pembayaran Menunggu Verifikasi',
            'msg' => 'Bukti pembayaran sudah dikirim. Silakan menunggu pemeriksaan petugas.',
            'tone' => 'bg-orange-50 border-orange-200 text-orange-700',
        ];
    } elseif ($statusPenyewaan === 'menunggu_pembayaran') {
        $panel = [
            'icon' => 'fa-check-circle',
            'title' => 'Pengajuan Disetujui',
            'msg' => 'Menunggu pembayaran. Silakan selesaikan pembayaran agar penyewaan dapat diproses.',
            'tone' => 'bg-green-50 border-green-200 text-green-700',
        ];
    } elseif ($statusPenyewaan === 'dibayar' || $statusPenyewaan === 'sedang_disewa') {
        if ($statusPengembalian === 'menunggu_inspeksi') {
            $panel = [
                'icon' => 'fa-hourglass-half',
                'title' => 'Menunggu Inspeksi',
                'msg' => 'Pengembalian telah dikirim. Petugas sedang memeriksa foto dan kondisi alat.',
                'tone' => 'bg-orange-50 border-orange-200 text-orange-700',
            ];
        } elseif ($statusPengembalian === 'diterima') {
            $panel = [
                'icon' => 'fa-check-circle',
                'title' => 'Pengembalian Diterima',
                'msg' => 'Alat telah diperiksa. Pengembalian berhasil dan tidak ada denda.',
                'tone' => 'bg-green-50 border-green-200 text-green-700',
            ];
        } elseif ($statusPengembalian === 'ditolak') {
            $panel = [
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Pengembalian Ditolak',
                'msg' => 'Hasil inspeksi menunjukkan terdapat masalah pada alat. Denda telah dibuat.',
                'tone' => 'bg-red-50 border-red-200 text-red-700',
            ];
        } else {
            $panel = [
                'icon' => 'fa-check-circle',
                'title' => 'Pembayaran Terverifikasi',
                'msg' => 'Penyewaan telah dibayar dan dapat digunakan.',
                'tone' => 'bg-blue-50 border-blue-200 text-blue-700',
            ];
        }
    } elseif ($statusPenyewaan === 'selesai') {
        $panel = [
            'icon' => 'fa-check-circle',
            'title' => 'Penyewaan Selesai',
            'msg' => 'Penyewaan telah selesai.',
            'tone' => 'bg-gray-50 border-gray-200 text-gray-600',
        ];
    } elseif ($statusPenyewaan === 'dibatalkan') {
        $panel = [
            'icon' => 'fa-ban',
            'title' => 'Dibatalkan',
            'msg' => 'Penyewaan ini telah dibatalkan.',
            'tone' => 'bg-red-50 border-red-200 text-red-700',
        ];
    }
@endphp

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('customer.dashboard') }}" class="hover:text-[#2A2A2A] transition">Dashboard</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <a href="{{ route('customer.my-rentals') }}" class="hover:text-[#2A2A2A] transition">My Rentals</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-[#2A2A2A] font-semibold">{{ $penyewaan->kode_penyewaan }}</span>
        </nav>

        {{-- Status Panel --}}
        @if($panel)
        <div class="mb-8">
            <div class="rounded-2xl border p-6 sm:p-8 flex items-start gap-4 {{ $panel['tone'] }}">
                <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas {{ $panel['icon'] }} text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider opacity-70 mb-1">Status Penyewaan</p>
                    <h2 class="text-lg font-extrabold mb-1">{{ $panel['title'] }}</h2>
                    <p class="text-sm leading-relaxed opacity-90">{{ $panel['msg'] }}</p>
                    @if($statusPengembalian === 'ditolak' && $penyewaan->dendas->isNotEmpty())
                        <p class="text-sm font-semibold mt-2">Status Denda: <span class="uppercase">{{ $penyewaan->dendas->first()->status }}</span></p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Rental Info --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-xl font-extrabold text-[#2A2A2A]">{{ $penyewaan->kode_penyewaan }}</h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$statusPenyewaan] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$statusPenyewaan] ?? $statusPenyewaan }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Pengajuan</p>
                            <p class="font-semibold text-[#2A2A2A] text-sm">{{ $penyewaan->tanggal_pengajuan->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Total Hari</p>
                            <p class="font-semibold text-[#2A2A2A] text-sm">{{ $penyewaan->total_hari }} hari</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Mulai</p>
                            <p class="font-semibold text-[#2A2A2A] text-sm">{{ $penyewaan->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Selesai</p>
                            <p class="font-semibold text-[#2A2A2A] text-sm">{{ $penyewaan->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($penyewaan->catatan)
                        <div class="bg-[#F5F5F3] rounded-xl p-4 mb-6">
                            <p class="text-xs text-gray-500 font-medium mb-1">Catatan</p>
                            <p class="text-[#2A2A2A] text-sm">{{ $penyewaan->catatan }}</p>
                        </div>
                    @endif

                    {{-- Equipment Details --}}
                    <h3 class="font-bold text-[#2A2A2A] mb-3">Equipment</h3>
                    <div class="space-y-3">
                        @foreach($penyewaan->detailPenyewaans as $detail)
                        <div class="flex items-center gap-4 bg-[#F5F5F3] rounded-xl p-4">
                            <div class="w-12 h-12 bg-[#2A2A2A] rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-hard-hat text-[#F7C264] text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#2A2A2A] text-sm truncate">{{ $detail->alat->nama_alat }}</p>
                                <p class="text-gray-500 text-xs">Rp {{ number_format($detail->harga_sewa, 0, ',', '.') }}/day × {{ $detail->jumlah }} unit</p>
                            </div>
                            <p class="font-bold text-[#2A2A2A] text-sm whitespace-nowrap">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Payment History --}}
                @if($penyewaan->pembayarans->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h3 class="font-bold text-[#2A2A2A] mb-4">Payment History</h3>
                    <div class="space-y-3">
                        @foreach($penyewaan->pembayarans as $bayar)
                        <div class="flex items-center justify-between bg-[#F5F5F3] rounded-xl p-4">
                            <div>
                                <p class="font-semibold text-[#2A2A2A] text-sm">{{ $bayar->kode_pembayaran }}</p>
                                <p class="text-gray-500 text-xs">{{ $bayar->tanggal_pembayaran->format('d M Y') }} · {{ ucfirst($bayar->metode_pembayaran) }} @if($bayar->denda_id)· Denda @endif</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-[#2A2A2A] text-sm">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</p>
                                @php
                                    $payStatusColors = ['pending' => 'text-yellow-600', 'diverifikasi' => 'text-green-600', 'ditolak' => 'text-red-600'];
                                    $payStatusLabels = ['pending' => 'Menunggu Verifikasi', 'diverifikasi' => 'Diverifikasi', 'ditolak' => 'Ditolak'];
                                @endphp
                                <p class="text-xs font-semibold {{ $payStatusColors[$bayar->status] ?? 'text-gray-600' }}">{{ $payStatusLabels[$bayar->status] ?? ucfirst($bayar->status) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Return Info --}}
                @if($penyewaan->pengembalian)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-[#2A2A2A]">Pengembalian</h3>
                        @php
                            $retStatus = [
                                'menunggu_inspeksi' => 'bg-orange-100 text-orange-700',
                                'diterima' => 'bg-green-100 text-green-700',
                                'ditolak' => 'bg-red-100 text-red-700',
                            ];
                            $retLabels = [
                                'menunggu_inspeksi' => 'Menunggu Inspeksi',
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $retStatus[$statusPengembalian] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $retLabels[$statusPengembalian] ?? ucfirst($statusPengembalian) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Kembali</p>
                            <p class="font-semibold text-[#2A2A2A] text-sm">{{ $penyewaan->pengembalian->tanggal_pengembalian->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Keterlambatan</p>
                            <p class="font-semibold text-[#2A2A2A] text-sm">
                                @if($penyewaan->pengembalian->terlambat_hari > 0)
                                    <span class="text-red-600">{{ $penyewaan->pengembalian->terlambat_hari }} hari</span>
                                @else
                                    <span class="text-green-600">Tepat waktu</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($penyewaan->pengembalian->kondisi_alat)
                    <div class="mt-4 bg-[#F5F5F3] rounded-xl p-4">
                        <p class="text-xs text-gray-500 font-medium mb-1">Deskripsi Kondisi</p>
                        <p class="text-[#2A2A2A] text-sm">{{ $penyewaan->pengembalian->kondisi_alat }}</p>
                    </div>
                    @endif
                    @if($penyewaan->pengembalian->catatan)
                    <div class="mt-4 bg-[#F5F5F3] rounded-xl p-4">
                        <p class="text-xs text-gray-500 font-medium mb-1">Catatan Pengembalian</p>
                        <p class="text-[#2A2A2A] text-sm">{{ $penyewaan->pengembalian->catatan }}</p>
                    </div>
                    @endif
                    @if($penyewaan->pengembalian->foto)
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 font-medium mb-2">Foto Pengembalian</p>
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($penyewaan->pengembalian->foto) }}" alt="Foto pengembalian" class="w-full max-h-72 object-cover rounded-xl border border-gray-200">
                    </div>
                    @endif
                </div>
                @endif

                {{-- Denda --}}
                @if($penyewaan->dendas->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h3 class="font-bold text-[#2A2A2A] mb-4">Denda</h3>
                    <div class="space-y-3">
                        @foreach($penyewaan->dendas as $denda)
                        @php
                            $dendaHasPendingPayment = $penyewaan->pembayarans->contains(fn ($p) => $p->denda_id === $denda->id && $p->status === 'pending');
                        @endphp
                        <div class="flex items-center justify-between bg-[#F5F5F3] rounded-xl p-4">
                            <div>
                                <p class="font-semibold text-[#2A2A2A] text-sm capitalize">{{ $denda->jenis_denda }}</p>
                                <p class="text-gray-500 text-xs">{{ $denda->alasan }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600 text-sm">Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</p>
                                @if($denda->status === 'pending' && ! $dendaHasPendingPayment)
                                <a href="{{ route('payment.denda.show', [$penyewaan->id, $denda->id]) }}" class="text-xs font-bold text-[#C48A1E] hover:text-[#9E6C14]">Bayar Sekarang</a>
                                @elseif($denda->status === 'pending' && $dendaHasPendingPayment)
                                <p class="text-xs font-semibold text-orange-600">Menunggu verifikasi</p>
                                @else
                                <p class="text-xs font-semibold text-green-600 capitalize">{{ $denda->status }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Summary Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-[#2A2A2A] mb-4">Payment Summary</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-semibold text-[#2A2A2A]">Rp {{ number_format($penyewaan->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($penyewaan->denda > 0)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Denda</span>
                            <span class="font-semibold text-red-600">+ Rp {{ number_format($penyewaan->denda, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between py-3">
                            <span class="font-bold text-[#2A2A2A]">Total</span>
                            <span class="font-extrabold text-[#2A2A2A] text-lg">Rp {{ number_format($penyewaan->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($statusPenyewaan === 'menunggu_pembayaran' && ! $hasPendingPayment)
                    <a href="{{ route('payment.show', $penyewaan->id) }}" class="block w-full text-center bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] py-3 rounded-xl font-bold text-sm transition mt-4 shadow-lg shadow-[#F7C264]/20">
                        <i class="fas fa-credit-card mr-2"></i>Lakukan Pembayaran
                    </a>
                    @endif

                    @if($statusPenyewaan === 'menunggu_pembayaran' && $hasPendingPayment)
                    <div class="mt-4 bg-orange-50 border border-orange-200 text-orange-700 rounded-xl p-3 text-center text-xs font-semibold">
                        <i class="fas fa-clock mr-1"></i>Pembayaran menunggu verifikasi
                    </div>
                    @endif

                    @if(in_array($statusPenyewaan, ['dibayar', 'sedang_disewa']) && ! $penyewaan->pengembalian)
                    <a href="{{ route('pengembalian.create', $penyewaan->id) }}" class="block w-full text-center bg-[#2A2A2A] hover:bg-gray-800 text-white py-3 rounded-xl font-bold text-sm transition mt-4">
                        <i class="fas fa-box-open mr-2"></i>Kembalikan Alat
                    </a>
                    @endif

                    @if($statusPengembalian === 'menunggu_inspeksi')
                    <div class="mt-4 bg-orange-50 border border-orange-200 text-orange-700 rounded-xl p-3 text-center text-xs font-semibold">
                        <i class="fas fa-hourglass-half mr-1"></i>Menunggu inspeksi petugas
                    </div>
                    @endif

                    @if($statusPenyewaan === 'pending')
                    <form method="POST" action="{{ route('rental.batal', $penyewaan->id) }}" class="mt-3" onsubmit="return confirm('Yakin ingin membatalkan penyewaan ini?');">
                        @csrf
                        <button type="submit" class="block w-full text-center border-2 border-red-200 text-red-600 hover:bg-red-50 py-3 rounded-xl font-bold text-sm transition">
                            <i class="fas fa-ban mr-2"></i>Batalkan Penyewaan
                        </button>
                    </form>
                    <p class="text-center text-gray-500 text-xs mt-3">
                        <i class="fas fa-hourglass-half mr-1"></i>Menunggu persetujuan
                    </p>
                    @endif

                    @if($statusPenyewaan === 'ditolak')
                    <div class="mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-center text-xs font-semibold">
                        <i class="fas fa-times-circle mr-1"></i>Pengajuan tidak dapat dilanjutkan
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection