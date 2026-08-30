@extends('layouts.app')

@section('title', 'My Rentals - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-[#2A2A2A]">My Rentals</h1>
                <p class="text-gray-500 mt-1">Track all your equipment rentals.</p>
            </div>
            <a href="{{ route('equipment.index') }}" class="bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] px-5 py-2.5 rounded-xl font-bold text-sm transition">
                <i class="fas fa-plus mr-1.5"></i>New Rental
            </a>
        </div>

        @if($penyewaans->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No rentals yet</h3>
                <p class="text-gray-500 text-sm mb-4">Start by browsing our equipment catalog.</p>
                <a href="{{ route('equipment.index') }}" class="inline-flex items-center gap-2 bg-[#2A2A2A] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#1a1a1a] transition">
                    Browse Equipment
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($penyewaans as $pw)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-bold text-[#2A2A2A]">{{ $pw->kode_penyewaan }}</span>
                                @php
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
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$pw->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$pw->status] ?? $pw->status }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500">
                                <span><i class="fas fa-calendar mr-1"></i>{{ $pw->tanggal_mulai->format('d M Y') }} - {{ $pw->tanggal_selesai->format('d M Y') }}</span>
                                <span><i class="fas fa-clock mr-1"></i>{{ $pw->total_hari }} days</span>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach($pw->detailPenyewaans as $detail)
                                    <span class="inline-flex items-center bg-[#F5F5F3] text-[#2A2A2A] text-xs font-semibold px-2.5 py-1 rounded-lg">
                                        {{ $detail->alat->nama_alat }} × {{ $detail->jumlah }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <p class="text-xl font-extrabold text-[#2A2A2A]">Rp {{ number_format($pw->total, 0, ',', '.') }}</p>
                            <a href="{{ route('customer.rental-detail', $pw->id) }}" class="text-sm text-[#2A2A2A] font-semibold hover:text-[#F7C264] transition">
                                View Details <i class="fas fa-arrow-right text-xs ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $penyewaans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
