@extends('layouts.app')

@section('title', $alat->nama_alat . ' - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('landing') }}" class="hover:text-[#2A2A2A] transition">Home</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <a href="{{ route('equipment.index') }}" class="hover:text-[#2A2A2A] transition">Equipment</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-[#2A2A2A] font-semibold">{{ $alat->nama_alat }}</span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Image --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-72 sm:h-96 bg-gradient-to-br from-[#2A2A2A] to-[#1a1a1a] flex items-center justify-center relative overflow-hidden">
                        @if($alat->gambar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('cross')->url($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-hard-hat text-[#F7C264] text-[120px]"></i>
                        @endif
                        <div class="absolute top-6 left-6">
                            <span class="bg-[#F7C264] text-[#2A2A2A] text-xs font-bold px-3 py-1.5 rounded-full uppercase">{{ $alat->kategori->nama_kategori }}</span>
                        </div>
                        <div class="absolute top-6 right-6">
                            @if($alat->stok_tersedia > 0)
                                <span class="bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">Tersedia</span>
                            @else
                                <span class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#2A2A2A]">{{ $alat->nama_alat }}</h1>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
                        <div class="bg-[#F5F5F3] rounded-xl p-4">
                            <p class="text-xs text-gray-500 font-medium mb-1">Kode</p>
                            <p class="font-bold text-[#2A2A2A] text-sm">{{ $alat->kode_alat }}</p>
                        </div>
                        <div class="bg-[#F5F5F3] rounded-xl p-4">
                            <p class="text-xs text-gray-500 font-medium mb-1">Kondisi</p>
                            <p class="font-bold text-[#2A2A2A] text-sm">{{ ucfirst($alat->kondisi) }}</p>
                        </div>
                        <div class="bg-[#F5F5F3] rounded-xl p-4">
                            <p class="text-xs text-gray-500 font-medium mb-1">Stok</p>
                            <p class="font-bold text-[#2A2A2A] text-sm">{{ $alat->stok_tersedia }} / {{ $alat->stok }} unit</p>
                        </div>
                        <div class="bg-[#F5F5F3] rounded-xl p-4">
                            <p class="text-xs text-gray-500 font-medium mb-1">Lokasi</p>
                            <p class="font-bold text-[#2A2A2A] text-sm">{{ $alat->lokasi->nama_lokasi }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-bold text-[#2A2A2A] mb-2">Deskripsi</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $alat->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-bold text-[#2A2A2A] mb-2">Lokasi</h3>
                        <p class="text-gray-600 text-sm"><i class="fas fa-map-marker-alt text-[#F7C264] mr-2"></i>{{ $alat->lokasi->alamat }}</p>
                        @if($alat->lokasi->keterangan)
                            <p class="text-gray-500 text-xs mt-1 ml-5">{{ $alat->lokasi->keterangan }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Price Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-500 font-medium">Harga Sewa</p>
                        <div class="flex items-baseline justify-center gap-1 mt-1">
                            <span class="text-3xl font-extrabold text-[#2A2A2A]">Rp {{ number_format($alat->harga_sewa_harian, 0, ',', '.') }}</span>
                            <span class="text-gray-500 text-sm">/day</span>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-semibold text-[#2A2A2A]">{{ $alat->kategori->nama_kategori }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Kondisi</span>
                            <span class="font-semibold text-[#2A2A2A]">{{ ucfirst($alat->kondisi) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Tersedia</span>
                            <span class="font-semibold text-[#2A2A2A]">{{ $alat->stok_tersedia }} unit</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-500">Status</span>
                            @if($alat->stok_tersedia > 0)
                                <span class="inline-flex items-center gap-1 text-green-600 font-semibold"><i class="fas fa-check-circle"></i> Tersedia</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-red-600 font-semibold"><i class="fas fa-times-circle"></i> Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->role === 'user')
                            @if($alat->stok_tersedia > 0)
                                <a href="{{ route('rental.create', $alat->id) }}" class="block w-full text-center bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
                                    <i class="fas fa-hand-holding mr-2"></i>RENT THIS EQUIPMENT
                                </a>
                            @else
                                <button disabled class="block w-full text-center bg-gray-300 text-gray-500 py-3.5 rounded-xl font-bold text-sm cursor-not-allowed">
                                    TIDAK TERSEDIA
                                </button>
                            @endif
                        @endif
                    @else
                        @if($alat->stok_tersedia > 0)
                            <a href="{{ route('login') }}?redirect={{ urlencode('/rental/create/' . $alat->id) }}" class="block w-full text-center bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
                                <i class="fas fa-hand-holding mr-2"></i>RENT THIS EQUIPMENT
                            </a>
                            <p class="text-center text-gray-500 text-xs mt-3">
                                <a href="{{ route('login') }}" class="text-[#2A2A2A] font-semibold hover:text-[#F7C264] transition">Login</a>
                                atau
                                <a href="{{ route('register') }}" class="text-[#2A2A2A] font-semibold hover:text-[#F7C264] transition">Daftar</a>
                                untuk menyewa
                            </p>
                        @else
                            <button disabled class="block w-full text-center bg-gray-300 text-gray-500 py-3.5 rounded-xl font-bold text-sm cursor-not-allowed">
                                TIDAK TERSEDIA
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- Related Equipment --}}
        @if($relatedAlat->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-xl font-extrabold text-[#2A2A2A] mb-6">Related Equipment</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedAlat as $related)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="relative h-40 bg-gradient-to-br from-[#2A2A2A] to-[#1a1a1a] flex items-center justify-center overflow-hidden">
                        @if($related->gambar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('cross')->url($related->gambar) }}" alt="{{ $related->nama_alat }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-hard-hat text-[#F7C264] text-4xl group-hover:scale-110 transition-transform"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-[#2A2A2A] text-sm group-hover:text-[#F7C264] transition">{{ $related->nama_alat }}</h3>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span class="font-extrabold text-[#2A2A2A]">Rp {{ number_format($related->harga_sewa_harian, 0, ',', '.') }}</span>
                            <span class="text-gray-500 text-xs">/day</span>
                        </div>
                        <a href="{{ route('equipment.show', $related->id) }}" class="block text-center mt-3 py-2 border border-gray-200 text-[#2A2A2A] rounded-lg font-bold text-xs hover:bg-[#2A2A2A] hover:text-white transition">
                            VIEW
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
