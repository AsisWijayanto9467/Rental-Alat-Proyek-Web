@extends('layouts.app')

@section('title', 'Contact Us - RENTAL PRO')

@section('content')
<div class="bg-[#F5F5F3] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-[#F7C264] text-sm font-bold uppercase tracking-wider">Contact Us</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#2A2A2A] mt-2">Get in Touch</h1>
            <p class="text-gray-500 mt-3 max-w-lg mx-auto">Have questions? We'd love to hear from you.</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-[#2A2A2A] mb-6">Send us a message</h2>
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition" placeholder="Your name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                                <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition" placeholder="your@email.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subject</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition" placeholder="How can we help?">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message</label>
                            <textarea rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition resize-none" placeholder="Tell us more..."></textarea>
                        </div>
                        <button type="submit" class="bg-[#2A2A2A] hover:bg-[#1a1a1a] text-white px-8 py-3 rounded-xl font-bold text-sm transition">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-[#F7C264] rounded-xl flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-[#2A2A2A]"></i>
                        </div>
                        <h3 class="font-bold text-[#2A2A2A]">Address</h3>
                    </div>
                    <p class="text-gray-500 text-sm">Jl. Pusat Gudang No. 1<br>Kebayoran Baru, Jakarta Selatan</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-[#F7C264] rounded-xl flex items-center justify-center">
                            <i class="fas fa-phone text-[#2A2A2A]"></i>
                        </div>
                        <h3 class="font-bold text-[#2A2A2A]">Phone</h3>
                    </div>
                    <p class="text-gray-500 text-sm">+62 812-3456-7890</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-[#F7C264] rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-[#2A2A2A]"></i>
                        </div>
                        <h3 class="font-bold text-[#2A2A2A]">Email</h3>
                    </div>
                    <p class="text-gray-500 text-sm">info@rentalpro.com</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-[#F7C264] rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-[#2A2A2A]"></i>
                        </div>
                        <h3 class="font-bold text-[#2A2A2A]">Hours</h3>
                    </div>
                    <p class="text-gray-500 text-sm">Mon - Sat: 08:00 - 17:00<br>Sunday: Closed</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
