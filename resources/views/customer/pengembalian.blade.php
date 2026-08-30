@extends('layouts.app')

@section('title', 'Proses Pengembalian - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-[#2A2A2A]">Kembalikan Alat</h1>
            <p class="text-gray-500 mt-1">Upload foto kondisi alat untuk pengembalian penyewaan {{ $penyewaan->kode_penyewaan }}. Foto akan diperiksa petugas.</p>
        </div>

        {{-- Rental Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-bold text-[#2A2A2A] mb-4">Ringkasan Penyewaan</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Kode Penyewaan</p>
                    <p class="font-semibold text-[#2A2A2A]">{{ $penyewaan->kode_penyewaan }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Mulai</p>
                    <p class="font-semibold text-[#2A2A2A]">{{ $penyewaan->tanggal_mulai->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Selesai</p>
                    <p class="font-semibold text-[#2A2A2A]">{{ $penyewaan->tanggal_selesai->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Total Hari</p>
                    <p class="font-semibold text-[#2A2A2A]">{{ $penyewaan->total_hari }} hari</p>
                </div>
            </div>

            <div class="mt-4 space-y-2">
                @foreach($penyewaan->detailPenyewaans as $detail)
                <div class="flex items-center justify-between bg-[#F5F5F3] rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#2A2A2A] rounded-lg flex items-center justify-center">
                            <i class="fas fa-hard-hat text-[#F7C264]"></i>
                        </div>
                        <div>
                            <p class="font-bold text-[#2A2A2A] text-sm">{{ $detail->alat->nama_alat }}</p>
                            <p class="text-gray-500 text-xs">{{ $detail->jumlah }} unit</p>
                        </div>
                    </div>
                    <p class="font-bold text-[#2A2A2A] text-sm">Rp {{ number_format($detail->harga_sewa * $detail->jumlah, 0, ',', '.') }}/hari</p>
                </div>
                @endforeach
            </div>

            <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 text-sm flex items-start gap-2">
                <i class="fas fa-info-circle mt-0.5"></i>
                <span>Jika tanggal pengembalian melebihi tanggal selesai, maka akan dikenakan <strong>denda keterlambatan</strong> secara otomatis.</span>
            </div>
        </div>

        {{-- Return Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <form method="POST" action="{{ route('pengembalian.store', $penyewaan->id) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="tanggal_pengembalian" class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" value="{{ old('tanggal_pengembalian', now()->toDateString()) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition">
                    @error('tanggal_pengembalian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Alat Kembali <span class="text-red-500">*</span></label>
                    <div x-data="{ preview: null }" class="space-y-3">
                        <div class="flex items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-xl hover:border-[#F7C264] transition cursor-pointer relative overflow-hidden"
                            @click="$refs.fotoInput.click()">
                            <template x-if="!preview">
                                <div class="text-center">
                                    <i class="fas fa-camera-retro text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Klik untuk upload foto pengembalian</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG (Maks 5MB)</p>
                                </div>
                            </template>
                            <template x-if="preview">
                                <img :src="preview" class="w-full h-full object-contain p-2">
                            </template>
                        </div>
                        <input type="file" name="foto" x-ref="fotoInput" accept="image/*" required class="hidden"
                            @change="if(event.target.files[0]) { const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(event.target.files[0]); }">
                    </div>
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="kondisi_alat" class="block text-sm font-semibold text-gray-700 mb-1.5">Kondisi Alat</label>
                    <textarea name="kondisi_alat" id="kondisi_alat" rows="3" placeholder="Contoh: alat kembali dalam kondisi baik dan bersih" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition resize-none">{{ old('kondisi_alat') }}</textarea>
                    @error('kondisi_alat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan (Opsional)</label>
                    <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan tambahan untuk pengembalian" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition resize-none">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
                        <i class="fas fa-box-open mr-2"></i>PROSES PENGEMBALIAN
                    </button>
                    <a href="{{ route('customer.rental-detail', $penyewaan->id) }}" class="flex-1 text-center border-2 border-gray-300 text-gray-700 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
