@extends('layouts.app')

@section('title', 'Dashboard - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-[#2A2A2A]">Dashboard</h1>
            <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->nama }}.</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-contract text-gray-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-[#2A2A2A]">{{ $totalPenyewaan }}</p>
                        <p class="text-xs text-gray-500 font-medium">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-yellow-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-[#2A2A2A]">{{ $pendingCount }}</p>
                        <p class="text-xs text-gray-500 font-medium">Menunggu Persetujuan</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-credit-card text-orange-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-[#2A2A2A]">{{ $paymentCount }}</p>
                        <p class="text-xs text-gray-500 font-medium">Menunggu Pembayaran</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tools text-blue-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-[#2A2A2A]">{{ $activeCount }}</p>
                        <p class="text-xs text-gray-500 font-medium">Sedang Disewa</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-[#2A2A2A] rounded-2xl p-6 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-white font-bold text-lg">Need equipment?</h3>
                <p class="text-gray-400 text-sm">Browse our catalog and find the right tools for your project.</p>
            </div>
            <a href="{{ route('equipment.index') }}" class="bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] px-6 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap">
                <i class="fas fa-search mr-1.5"></i>Browse Equipment
            </a>
        </div>

        {{-- Recent Rentals --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-[#2A2A2A]">Recent Rentals</h3>
                @if($recentPenyewaan->isNotEmpty())
                    <a href="{{ route('customer.my-rentals') }}" class="text-sm text-[#2A2A2A] font-semibold hover:text-[#F7C264] transition">View All</a>
                @endif
            </div>
            @if($recentPenyewaan->isEmpty())
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No rentals yet</p>
                    <p class="text-gray-400 text-sm mt-1">Start by browsing our equipment catalog.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentPenyewaan as $pw)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-[#2A2A2A]">{{ $pw->kode_penyewaan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $pw->tanggal_pengajuan->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-[#2A2A2A]">Rp {{ number_format($pw->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
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
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('customer.rental-detail', $pw->id) }}" class="text-sm text-[#2A2A2A] font-semibold hover:text-[#F7C264] transition">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
