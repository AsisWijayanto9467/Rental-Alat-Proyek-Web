@extends('layouts.app')

@section('title', 'How It Works - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-[#F7C264] text-sm font-bold uppercase tracking-wider">How It Works</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#2A2A2A] mt-2">Rent Equipment in 4 Simple Steps</h1>
            <p class="text-gray-500 mt-3 max-w-lg mx-auto">Our streamlined rental process makes it easy to get the equipment you need.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 relative overflow-hidden">
                <div class="absolute top-4 right-4 text-[#F7C264] text-6xl font-extrabold opacity-10">01</div>
                <div class="w-14 h-14 bg-[#F7C264] rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-search text-[#2A2A2A] text-xl"></i>
                </div>
                <h3 class="font-bold text-[#2A2A2A] text-xl mb-2">Browse & Select</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Browse our equipment catalog. Filter by category, location, or search by name. Find the perfect equipment for your project.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 relative overflow-hidden">
                <div class="absolute top-4 right-4 text-[#F7C264] text-6xl font-extrabold opacity-10">02</div>
                <div class="w-14 h-14 bg-[#F7C264] rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-alt text-[#2A2A2A] text-xl"></i>
                </div>
                <h3 class="font-bold text-[#2A2A2A] text-xl mb-2">Choose Dates</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Select your rental start and end dates. Choose the quantity you need. Review the price summary before submitting.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 relative overflow-hidden">
                <div class="absolute top-4 right-4 text-[#F7C264] text-6xl font-extrabold opacity-10">03</div>
                <div class="w-14 h-14 bg-[#F7C264] rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-credit-card text-[#2A2A2A] text-xl"></i>
                </div>
                <h3 class="font-bold text-[#2A2A2A] text-xl mb-2">Make Payment</h3>
                <p class="text-gray-500 text-sm leading-relaxed">After your rental is approved, complete payment via cash, bank transfer, or QRIS. Upload your payment proof for verification.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 relative overflow-hidden">
                <div class="absolute top-4 right-4 text-[#F7C264] text-6xl font-extrabold opacity-10">04</div>
                <div class="w-14 h-14 bg-[#F7C264] rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-truck-loading text-[#2A2A2A] text-xl"></i>
                </div>
                <h3 class="font-bold text-[#2A2A2A] text-xl mb-2">Get Equipment</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Equipment is prepared at our depot. Pick it up or arrange delivery to your project site. Return when your rental period ends.</p>
            </div>
        </div>

        <div class="text-center mt-16">
            <a href="{{ route('equipment.index') }}" class="inline-flex items-center gap-2 bg-[#F7C264] hover:bg-[#e5a83b] text-[#2A2A2A] px-8 py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-[#F7C264]/20">
                START BROWSING <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection
