@extends('layouts.app')

@section('title', 'Rental Detail - RENTAL PRO')

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

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Rental Info --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-xl font-extrabold text-[#2A2A2A]">{{ $penyewaan->kode_penyewaan }}</h1>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'disetujui' => 'bg-blue-100 text-blue-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                                'menunggu_pembayaran' => 'bg-orange-100 text-orange-800',
                                'dibayar' => 'bg-green-100 text-green-800',
                                'sedang_disewa' => 'bg-purple-100 text-purple-800',
                                'selesai' => 'bg-gray-100 text-gray-600',
                                'dibatalkan' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'pending' => 'Pending',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'dibayar' => 'Dibayar',
                                'sedang_disewa' => 'Sedang Disewa',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$penyewaan->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$penyewaan->status] ?? $penyewaan->status }}
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
                                <p class="text-gray-500 text-xs">{{ $bayar->tanggal_pembayaran->format('d M Y') }} · {{ ucfirst($bayar->metode_pembayaran) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-[#2A2A2A] text-sm">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</p>
                                @php
                                    $payStatusColors = ['pending' => 'text-yellow-600', 'diverifikasi' => 'text-green-600', 'ditolak' => 'text-red-600'];
                                @endphp
                                <p class="text-xs font-semibold {{ $payStatusColors[$bayar->status] ?? 'text-gray-600' }}">{{ ucfirst($bayar->status) }}</p>
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

                    @if($penyewaan->status === 'menunggu_pembayaran' || $penyewaan->status === 'disetujui')
                    <a href="{{ route('payment.show', $penyewaan->id) }}" class="block w-full text-center bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] py-3 rounded-xl font-bold text-sm transition mt-4 shadow-lg shadow-[#F7C264]/20">
                        <i class="fas fa-credit-card mr-2"></i>Upload Payment
                    </a>
                    @endif

                    @if($penyewaan->status === 'pending')
                    <p class="text-center text-gray-500 text-xs mt-4">
                        <i class="fas fa-clock mr-1"></i>Menunggu persetujuan admin
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
