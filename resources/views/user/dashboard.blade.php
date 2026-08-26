@extends('layouts.app')

@section('title', 'RENTAL PRO - Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-accent-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hard-hat text-brand-900 text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-brand-900 leading-none">RENTAL PRO</h1>
                        <p class="text-[10px] text-gray-500 font-medium">Construction Equipment Rental</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->nama }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-xl hover:bg-gray-100 transition">
                            <div class="w-9 h-9 bg-brand-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100 sm:hidden">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->nama }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-home w-4"></i> Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-4"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->nama }}!</h2>
            <p class="text-gray-500 mt-1">Kelola penyewaan alat proyek Anda dari sini.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-contract text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $totalPenyewaan }}</p>
                        <p class="text-sm text-gray-500 font-medium">Total Penyewaan</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-amber-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $penyewaanAktif }}</p>
                        <p class="text-sm text-gray-500 font-medium">Sedang Aktif</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tools text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $totalAlat }}</p>
                        <p class="text-sm text-gray-500 font-medium">Alat Tersedia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Penyewaan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Penyewaan Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                @if($recentPenyewaan->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada penyewaan</p>
                        <p class="text-gray-400 text-sm mt-1">Mulai sewa alat proyek sekarang!</p>
                    </div>
                @else
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentPenyewaan as $pw)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-brand-600">{{ $pw->kode_penyewaan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pw->tanggal_pengajuan->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($pw->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'disetujui' => 'bg-blue-100 text-blue-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                            'menunggu_pembayaran' => 'bg-orange-100 text-orange-800',
                                            'dibayar' => 'bg-green-100 text-green-800',
                                            'sedang_disewa' => 'bg-purple-100 text-purple-800',
                                            'selesai' => 'bg-gray-100 text-gray-800',
                                            'dibatalkan' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Menunggu',
                                            'disetujui' => 'Disetujui',
                                            'ditolak' => 'Ditolak',
                                            'menunggu_pembayaran' => 'Bayar',
                                            'dibayar' => 'Dibayar',
                                            'sedang_disewa' => 'Disewa',
                                            'selesai' => 'Selesai',
                                            'dibatalkan' => 'Dibatalkan',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$pw->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$pw->status] ?? $pw->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
