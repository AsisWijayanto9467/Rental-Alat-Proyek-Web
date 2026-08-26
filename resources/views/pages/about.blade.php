@extends('layouts.app')

@section('title', 'About Us - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3]">
    {{-- Hero --}}
    <section class="bg-[#2A2A2A] py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-[#F7C264] text-sm font-bold uppercase tracking-wider">About Us</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Your Trusted Equipment Rental Partner</h1>
            <p class="text-gray-400 mt-3 max-w-2xl mx-auto">Providing reliable construction equipment since 2015. We understand the demands of construction projects and deliver solutions that keep your work moving.</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#2A2A2A] mb-4">About Rental Pro</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">Rental Pro is a leading construction equipment rental company based in Indonesia. We provide a comprehensive range of heavy equipment and machinery for construction, infrastructure, and industrial projects.</p>
                    <p class="text-gray-600 leading-relaxed mb-4">With over 150 units of equipment across 3 locations, we serve projects of every scale — from small residential builds to large infrastructure developments.</p>
                    <p class="text-gray-600 leading-relaxed">Our commitment to quality, safety, and customer satisfaction has made us the preferred partner for contractors and project managers across the region.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                        <div class="text-3xl font-extrabold text-[#F7C264]">150+</div>
                        <p class="text-gray-500 text-sm mt-1">Equipment Units</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                        <div class="text-3xl font-extrabold text-[#F7C264]">500+</div>
                        <p class="text-gray-500 text-sm mt-1">Projects Served</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                        <div class="text-3xl font-extrabold text-[#F7C264]">3</div>
                        <p class="text-gray-500 text-sm mt-1">Locations</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                        <div class="text-3xl font-extrabold text-[#F7C264]">10+</div>
                        <p class="text-gray-500 text-sm mt-1">Years Experience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-extrabold text-[#2A2A2A] text-center mb-12">Why Choose Us</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#F7C264] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-[#2A2A2A] text-xl"></i>
                    </div>
                    <h3 class="font-bold text-[#2A2A2A] text-lg mb-2">Quality Equipment</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">All equipment is regularly maintained and inspected to ensure optimal performance and safety.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#F7C264] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-usd text-[#2A2A2A] text-xl"></i>
                    </div>
                    <h3 class="font-bold text-[#2A2A2A] text-lg mb-2">Transparent Pricing</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">No hidden fees. Our pricing is clear and competitive, with daily rates that fit your budget.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#F7C264] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-[#2A2A2A] text-xl"></i>
                    </div>
                    <h3 class="font-bold text-[#2A2A2A] text-lg mb-2">Expert Support</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Our team of experienced professionals is ready to help you choose the right equipment.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
