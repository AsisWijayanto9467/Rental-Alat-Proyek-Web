@extends('layouts.app')

@section('title', 'Payment - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-[#2A2A2A]">Upload Payment</h1>
            <p class="text-gray-500 mt-1">Upload your payment proof for rental {{ $penyewaan->kode_penyewaan }}.</p>
        </div>

        {{-- Payment Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-bold text-[#2A2A2A] mb-4">Payment Summary</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Kode Penyewaan</span>
                    <span class="font-semibold text-[#2A2A2A]">{{ $penyewaan->kode_penyewaan }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-semibold text-[#2A2A2A]">{{ $penyewaan->tanggal_mulai->format('d M Y') }} - {{ $penyewaan->tanggal_selesai->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between py-3">
                    <span class="font-bold text-[#2A2A2A]">Total</span>
                    <span class="font-extrabold text-[#2A2A2A] text-lg">Rp {{ number_format($penyewaan->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Payment Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <form method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="penyewaan_id" value="{{ $penyewaan->id }}">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="transfer" {{ old('metode_pembayaran') === 'transfer' ? 'checked' : '' }} class="peer sr-only" required>
                            <div class="border-2 border-gray-200 peer-checked:border-[#F7C264] peer-checked:bg-[#FFF9EB] rounded-xl p-4 text-center transition hover:border-gray-300">
                                <i class="fas fa-university text-gray-400 peer-checked:text-[#F7C264] text-xl mb-2"></i>
                                <p class="text-sm font-semibold text-gray-700">Transfer</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="cash" {{ old('metode_pembayaran') === 'cash' ? 'checked' : '' }} class="peer sr-only">
                            <div class="border-2 border-gray-200 peer-checked:border-[#F7C264] peer-checked:bg-[#FFF9EB] rounded-xl p-4 text-center transition hover:border-gray-300">
                                <i class="fas fa-money-bill-wave text-gray-400 peer-checked:text-[#F7C264] text-xl mb-2"></i>
                                <p class="text-sm font-semibold text-gray-700">Cash</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="qris" {{ old('metode_pembayaran') === 'qris' ? 'checked' : '' }} class="peer sr-only">
                            <div class="border-2 border-gray-200 peer-checked:border-[#F7C264] peer-checked:bg-[#FFF9EB] rounded-xl p-4 text-center transition hover:border-gray-300">
                                <i class="fas fa-qrcode text-gray-400 peer-checked:text-[#F7C264] text-xl mb-2"></i>
                                <p class="text-sm font-semibold text-gray-700">QRIS</p>
                            </div>
                        </label>
                    </div>
                    @error('metode_pembayaran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bukti Pembayaran</label>
                    <div x-data="{ preview: null }" class="space-y-3">
                        <div class="flex items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-xl hover:border-[#F7C264] transition cursor-pointer relative overflow-hidden"
                            @click="$refs.fileInput.click()">
                            <template x-if="!preview">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Klik untuk upload gambar</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG (Maks 2MB)</p>
                                </div>
                            </template>
                            <template x-if="preview">
                                <img :src="preview" class="w-full h-full object-contain p-2">
                            </template>
                        </div>
                        <input type="file" name="bukti_pembayaran" x-ref="fileInput" accept="image/*" required class="hidden"
                            @change="if(event.target.files[0]) { const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(event.target.files[0]); }">
                    </div>
                    @error('bukti_pembayaran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition resize-none" placeholder="Contoh: transfer via BCA, atas nama PT XYZ...">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
                        <i class="fas fa-upload mr-2"></i>SUBMIT PAYMENT
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
