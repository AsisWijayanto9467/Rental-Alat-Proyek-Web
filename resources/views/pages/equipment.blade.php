@extends('layouts.app')

@section('title', 'Equipment Catalog - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-[#2A2A2A]">Equipment Catalog</h1>
            <p class="text-gray-500 mt-1">Find the right equipment for your project.</p>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('equipment.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Search</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search equipment..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Category</label>
                    <select name="kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition bg-white">
                        <option value="">All Categories</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->nama_kategori }}" {{ request('kategori') === $k->nama_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Location</label>
                    <select name="lokasi" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition bg-white">
                        <option value="">All Locations</option>
                        @foreach($lokasis as $l)
                            <option value="{{ $l->nama_lokasi }}" {{ request('lokasi') === $l->nama_lokasi ? 'selected' : '' }}>{{ $l->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Sort By</label>
                    <select name="sort" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition bg-white">
                        <option value="">Newest</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <button type="submit" class="bg-[#2A2A2A] hover:bg-[#1a1a1a] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-filter mr-1.5"></i>Filter
                </button>
                <a href="{{ route('equipment.index') }}" class="text-gray-500 hover:text-[#2A2A2A] text-sm font-semibold transition">Clear</a>
            </div>
        </form>

        {{-- Results Count --}}
        <div class="mb-6">
            <p class="text-sm text-gray-500">Showing <span class="font-bold text-[#2A2A2A]">{{ $alats->total() }}</span> equipment</p>
        </div>

        {{-- Equipment Grid --}}
        @if($alats->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No equipment found</h3>
                <p class="text-gray-500 text-sm">Try adjusting your search or filter criteria.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($alats as $alat)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="relative h-52 bg-gradient-to-br from-[#2A2A2A] to-[#1a1a1a] flex items-center justify-center overflow-hidden">
                        @if($alat->gambar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('cross')->url($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-hard-hat text-[#F7C264] text-6xl group-hover:scale-110 transition-transform duration-500"></i>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="bg-[#F7C264] text-[#2A2A2A] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">{{ $alat->kategori->nama_kategori }}</span>
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

            <div class="mt-8">
                {{ $alats->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
