@extends('layouts.app')

@section('title', 'Rent ' . $alat->nama_alat . ' - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('equipment.index') }}" class="hover:text-[#2A2A2A] transition">Equipment</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <a href="{{ route('equipment.show', $alat->id) }}" class="hover:text-[#2A2A2A] transition">{{ $alat->nama_alat }}</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-[#2A2A2A] font-semibold">Rent</span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h1 class="text-xl font-extrabold text-[#2A2A2A] mb-6">Rental Form</h1>

                    <form method="POST" action="{{ route('rental.store') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="alat_id" value="{{ $alat->id }}">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('tanggal_mulai') border-red-500 @enderror">
                                @error('tanggal_mulai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('tanggal_selesai') border-red-500 @enderror">
                                @error('tanggal_selesai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Unit</label>
                            <div x-data="{ qty: {{ old('jumlah', 1) }} }" class="flex items-center gap-3">
                                <button type="button" @click="qty = Math.max(1, qty - 1); $refs.qtyInput.value = qty" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <input type="number" name="jumlah" x-ref="qtyInput" x-model="qty" min="1" max="{{ $alat->stok_tersedia }}" required
                                    class="w-20 text-center px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('jumlah') border-red-500 @enderror">
                                <button type="button" @click="qty = Math.min({{ $alat->stok_tersedia }}, qty + 1); $refs.qtyInput.value = qty" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                                <span class="text-gray-500 text-sm">/ {{ $alat->stok_tersedia }} available</span>
                            </div>
                            @error('jumlah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition resize-none" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="submit" class="flex-1 bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
                                <i class="fas fa-paper-plane mr-2"></i>SUBMIT RENTAL
                            </button>
                            <a href="{{ route('equipment.show', $alat->id) }}" class="flex-1 text-center border-2 border-gray-300 text-gray-700 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="flex items-center gap-3 mb-4">
                        @if($alat->gambar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('cross')->url($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-12 h-12 rounded-xl object-cover shrink-0">
                        @else
                            <div class="w-12 h-12 bg-[#2A2A2A] rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-hard-hat text-[#F7C264]"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-[#2A2A2A] text-sm">{{ $alat->nama_alat }}</h3>
                            <p class="text-gray-500 text-xs">{{ $alat->kategori->nama_kategori }}</p>
                        </div>
                    </div>
                    <hr class="my-4 border-gray-100">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Harga/Hari</span>
                            <span class="font-semibold text-[#2A2A2A]">Rp {{ number_format($alat->harga_sewa_harian, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Lokasi</span>
                            <span class="font-semibold text-[#2A2A2A] text-right">{{ $alat->lokasi->nama_lokasi }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kondisi</span>
                            <span class="font-semibold text-[#2A2A2A]">{{ ucfirst($alat->kondisi) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Stok</span>
                            <span class="font-semibold text-[#2A2A2A]">{{ $alat->stok_tersedia }} unit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
