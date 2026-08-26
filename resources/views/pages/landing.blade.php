@extends('layouts.app')

@section('title', 'RENTAL PRO - Construction Equipment Rental')

@section('content')
{{-- Hero Section --}}
<section class="hero-gradient relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 right-10 w-96 h-96 bg-[#F7C264] rounded-full blur-[120px]"></div>
        <div class="absolute bottom-10 left-10 w-72 h-72 bg-[#F7C264] rounded-full blur-[100px]"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-[#F7C264] rounded-full animate-pulse"></span>
                    <span class="text-gray-300 text-xs font-medium">Trusted by 500+ Projects Across Indonesia</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    RELIABLE EQUIPMENT<br>
                    <span class="text-[#F7C264]">FOR YOUR NEXT</span><br>
                    PROJECT
                </h1>
                <p class="text-gray-400 text-lg leading-relaxed mb-8 max-w-lg">
                    Find and rent reliable construction equipment for projects of every scale. From excavators to generators, we've got you covered.
                </p>
                <div class="flex flex-wrap gap-4 mb-12">
                    <a href="{{ route('equipment.index') }}" class="inline-flex items-center gap-2 bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] px-8 py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
                        BROWSE EQUIPMENT
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('how-it-works') }}" class="inline-flex items-center gap-2 border border-white/20 text-white hover:bg-white/10 px-8 py-3.5 rounded-xl font-bold text-sm transition">
                        HOW IT WORKS
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <div class="text-2xl font-extrabold text-white">150+</div>
                        <div class="text-gray-500 text-xs font-medium mt-0.5">Units Available</div>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-white">3</div>
                        <div class="text-gray-500 text-xs font-medium mt-0.5">Locations</div>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-white">500+</div>
                        <div class="text-gray-500 text-xs font-medium mt-0.5">Projects Done</div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8">
                    <div class="bg-gradient-to-br from-[#F7C264]/20 to-[#F7C264]/5 rounded-2xl p-12 text-center">
                        <i class="fas fa-hard-hat text-[#F7C264] text-8xl mb-6"></i>
                        <h3 class="text-white text-xl font-bold mb-2">Professional Equipment</h3>
                        <p class="text-gray-400 text-sm">Ready for your next project</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <i class="fas fa-cog text-[#F7C264] text-xl mb-2"></i>
                            <p class="text-white text-xs font-semibold">Flexible Rental</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <i class="fas fa-headset text-[#F7C264] text-xl mb-2"></i>
                            <p class="text-white text-xs font-semibold">Reliable Support</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <i class="fas fa-tags text-[#F7C264] text-xl mb-2"></i>
                            <p class="text-white text-xs font-semibold">Fair Pricing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Categories Section --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-[#F7C264] text-sm font-bold uppercase tracking-wider">Our Categories</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2A2A2A] mt-2">Equipment by Category</h2>
            <p class="text-gray-500 mt-3 max-w-lg mx-auto">Choose from our wide range of construction equipment categories.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($kategoris as $kategori)
            <a href="{{ route('equipment.index', ['kategori' => $kategori->nama_kategori]) }}" class="group bg-[#F5F5F3] hover:bg-[#F7C264] rounded-2xl p-6 text-center transition-all duration-300 hover:shadow-lg hover:shadow-[#F7C264]/10">
                <div class="w-14 h-14 bg-[#2A2A2A] group-hover:bg-white rounded-xl flex items-center justify-center mx-auto mb-3 transition">
                    <i class="fas fa-hard-hat text-[#F7C264] group-hover:text-[#2A2A2A] text-xl transition"></i>
                </div>
                <h3 class="font-bold text-[#2A2A2A] text-sm">{{ $kategori->nama_kategori }}</h3>
                <p class="text-gray-500 group-hover:text-[#2A2A2A]/70 text-xs mt-1">{{ $kategori->jumlah_alat }} units</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Equipment --}}
<section class="py-20 bg-[#F5F5F3]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-12 gap-4">
            <div>
                <span class="text-[#F7C264] text-sm font-bold uppercase tracking-wider">Featured Equipment</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2A2A2A] mt-2">Popular Rentals</h2>
            </div>
            <a href="{{ route('equipment.index') }}" class="inline-flex items-center gap-2 text-[#2A2A2A] font-bold text-sm hover:text-[#F7C264] transition">
                VIEW ALL EQUIPMENT <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredAlat as $alat)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div class="relative h-52 bg-gradient-to-br from-[#2A2A2A] to-[#1a1a1a] flex items-center justify-center overflow-hidden">
                    <i class="fas fa-hard-hat text-[#F7C264] text-6xl group-hover:scale-110 transition-transform duration-500"></i>
                    <div class="absolute top-4 left-4">
                        <span class="bg-[#F7C264] text-[#2A2A2A] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">{{ $alat->kategori->nama_kategori }}</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        @if($alat->stok_tersedia > 0)
                            <span class="bg-green-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">Tersedia</span>
                        @else
                            <span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">Full</span>
                        @endif
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-[#2A2A2A] text-lg group-hover:text-[#F7C264] transition">{{ $alat->nama_alat }}</h3>
                    <div class="flex items-center gap-1.5 mt-2 text-gray-500 text-sm">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                        <span>{{ $alat->lokasi->nama_lokasi }}</span>
                    </div>
                    <div class="flex items-baseline gap-1 mt-3">
                        <span class="text-2xl font-extrabold text-[#2A2A2A]">Rp {{ number_format($alat->harga_sewa_harian, 0, ',', '.') }}</span>
                        <span class="text-gray-500 text-sm">/day</span>
                    </div>
                    <div class="flex items-center gap-2 mt-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1"><i class="fas fa-box text-green-500"></i> {{ $alat->stok_tersedia }} unit</span>
                        <span class="flex items-center gap-1"><i class="fas fa-check-circle text-green-500"></i> {{ ucfirst($alat->kondisi) }}</span>
                    </div>
                    <a href="{{ route('equipment.show', $alat->id) }}" class="block w-full text-center mt-4 py-2.5 border-2 border-[#2A2A2A] text-[#2A2A2A] rounded-xl font-bold text-sm hover:bg-[#2A2A2A] hover:text-white transition">
                        VIEW DETAILS
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-[#F7C264] text-sm font-bold uppercase tracking-wider">How It Works</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2A2A2A] mt-2">Rent in 4 Easy Steps</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center relative">
                <div class="w-16 h-16 bg-[#F7C264] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-[#2A2A2A] text-xl"></i>
                </div>
                <div class="text-[#F7C264] text-5xl font-extrabold absolute -top-2 -right-2 lg:right-8 opacity-10">01</div>
                <h3 class="font-bold text-[#2A2A2A] text-lg mb-2">Browse Equipment</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Find the right equipment for your project from our catalog.</p>
            </div>
            <div class="text-center relative">
                <div class="w-16 h-16 bg-[#F7C264] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-check text-[#2A2A2A] text-xl"></i>
                </div>
                <div class="text-[#F7C264] text-5xl font-extrabold absolute -top-2 -right-2 lg:right-8 opacity-10">02</div>
                <h3 class="font-bold text-[#2A2A2A] text-lg mb-2">Select Dates</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Choose your rental period and confirm the quantity.</p>
            </div>
            <div class="text-center relative">
                <div class="w-16 h-16 bg-[#F7C264] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-[#2A2A2A] text-xl"></i>
                </div>
                <div class="text-[#F7C264] text-5xl font-extrabold absolute -top-2 -right-2 lg:right-8 opacity-10">03</div>
                <h3 class="font-bold text-[#2A2A2A] text-lg mb-2">Make Payment</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Complete payment via cash, transfer, or QRIS.</p>
            </div>
            <div class="text-center relative">
                <div class="w-16 h-16 bg-[#F7C264] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-[#2A2A2A] text-xl"></i>
                </div>
                <div class="text-[#F7C264] text-5xl font-extrabold absolute -top-2 -right-2 lg:right-8 opacity-10">04</div>
                <h3 class="font-bold text-[#2A2A2A] text-lg mb-2">Get Equipment</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Equipment is ready for pickup or delivery to your site.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-[#2A2A2A] relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#F7C264] rounded-full blur-[150px]"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Ready to Start Your Project?</h2>
        <p class="text-gray-400 text-lg mb-8 max-w-2xl mx-auto">Browse our equipment catalog and find the perfect tools for your construction needs.</p>
        <a href="{{ route('equipment.index') }}" class="inline-flex items-center gap-2 bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] px-8 py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
            BROWSE EQUIPMENT <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>
@endsection
