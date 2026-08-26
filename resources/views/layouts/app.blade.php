<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RENTAL PRO - Construction Equipment Rental')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'manrope': ['Manrope', 'sans-serif'],
                    },
                    colors: {
                        'brand': {
                            50: '#FFF9EB',
                            100: '#FFF0CC',
                            200: '#FFE6A3',
                            300: '#F7D47D',
                            400: '#F7C264',
                            500: '#E5A83B',
                            600: '#C48A1E',
                            700: '#9E6C14',
                            800: '#7A5410',
                            900: '#5C3F0C',
                        },
                        'charcoal': {
                            50: '#f6f6f6',
                            100: '#e7e7e7',
                            200: '#d1d1d1',
                            300: '#b0b0b0',
                            400: '#888888',
                            500: '#6d6d6d',
                            600: '#5d5d5d',
                            700: '#4f4f4f',
                            800: '#3d3d3d',
                            900: '#2A2A2A',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #2A2A2A 0%, #1a1a1a 50%, #2A2A2A 100%);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F5F5F3] min-h-screen flex flex-col">
    {{-- Navbar --}}
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 lg:h-20">
                {{-- Logo --}}
                <a href="{{ route('landing') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="w-10 h-10 bg-[#F7C264] rounded-lg flex items-center justify-center">
                        <i class="fas fa-hard-hat text-[#2A2A2A] text-lg"></i>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-[#2A2A2A] tracking-tight">RENTAL PRO</span>
                        <span class="block text-[10px] text-gray-500 font-medium -mt-0.5">Construction Equipment</span>
                    </div>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('landing') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-[#2A2A2A] rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('landing') ? 'text-[#2A2A2A] bg-gray-100' : '' }}">Home</a>
                    <a href="{{ route('equipment.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-[#2A2A2A] rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('equipment.*') ? 'text-[#2A2A2A] bg-gray-100' : '' }}">Equipment</a>
                    <a href="{{ route('how-it-works') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-[#2A2A2A] rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('how-it-works') ? 'text-[#2A2A2A] bg-gray-100' : '' }}">How It Works</a>
                    <a href="{{ route('about') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-[#2A2A2A] rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('about') ? 'text-[#2A2A2A] bg-gray-100' : '' }}">About</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-[#2A2A2A] rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('contact') ? 'text-[#2A2A2A] bg-gray-100' : '' }}">Contact</a>
                </div>

                {{-- Desktop Auth --}}
                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->role === 'user')
                            <a href="{{ route('customer.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-[#2A2A2A] rounded-lg hover:bg-gray-100 transition">Dashboard</a>
                            <a href="{{ route('customer.my-rentals') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-[#2A2A2A] rounded-lg hover:bg-gray-100 transition">My Rentals</a>
                            <div class="w-px h-6 bg-gray-200"></div>
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                    <div class="w-8 h-8 bg-[#F7C264] rounded-full flex items-center justify-center text-[#2A2A2A] font-bold text-xs">
                                        {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 max-w-[120px] truncate">{{ auth()->user()->nama }}</span>
                                    <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-user w-4 text-gray-400"></i> Profile
                                    </a>
                                    <hr class="my-1 border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt w-4"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-red-600 transition">Logout</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-[#2A2A2A] border border-gray-300 rounded-lg hover:bg-gray-50 transition">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-[#2A2A2A] bg-[#F7C264] rounded-lg hover:bg-[#e5a83b] transition shadow-sm">Register</a>
                    @endauth
                </div>

                {{-- Mobile Toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden flex items-center p-2 rounded-lg hover:bg-gray-100">
                    <i :class="mobileOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-gray-700 text-lg"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition class="lg:hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('landing') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">Home</a>
                <a href="{{ route('equipment.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">Equipment</a>
                <a href="{{ route('how-it-works') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">How It Works</a>
                <a href="{{ route('about') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">About</a>
                <a href="{{ route('contact') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">Contact</a>
                <hr class="my-2 border-gray-200">
                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">Dashboard</a>
                        <a href="{{ route('customer.my-rentals') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">My Rentals</a>
                        <a href="{{ route('customer.profile') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-semibold text-red-600 rounded-lg hover:bg-red-50">Logout</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">Login</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2.5 text-sm font-semibold text-[#2A2A2A] bg-[#F7C264] rounded-lg text-center mt-2">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#2A2A2A] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-10 h-10 bg-[#F7C264] rounded-lg flex items-center justify-center">
                            <i class="fas fa-hard-hat text-[#2A2A2A] text-lg"></i>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold tracking-tight">RENTAL PRO</span>
                            <span class="block text-[10px] text-gray-400 font-medium -mt-0.5">Construction Equipment</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Solusi terpercaya penyewaan alat berat dan peralatan konstruksi untuk proyek di seluruh Indonesia.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('equipment.index') }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">Equipment</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">How It Works</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">Contact</a></li>
                    </ul>
                </div>

                {{-- Equipment --}}
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4">Equipment</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('equipment.index', ['kategori' => 'Excavator']) }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">Excavator</a></li>
                        <li><a href="{{ route('equipment.index', ['kategori' => 'Bulldozer']) }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">Bulldozer</a></li>
                        <li><a href="{{ route('equipment.index', ['kategori' => 'Generator']) }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">Generator</a></li>
                        <li><a href="{{ route('equipment.index', ['kategori' => 'Lifting Equipment']) }}" class="text-gray-400 text-sm hover:text-[#F7C264] transition">Lifting</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4">Contact Us</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-[#F7C264] text-sm mt-0.5"></i>
                            <span class="text-gray-400 text-sm">Jl. Pusat Gudang No. 1, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-[#F7C264] text-sm"></i>
                            <span class="text-gray-400 text-sm">+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-[#F7C264] text-sm"></i>
                            <span class="text-gray-400 text-sm">info@rentalpro.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-gray-700 my-10">

            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Rental Pro. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-gray-500 hover:text-[#F7C264] transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-500 hover:text-[#F7C264] transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-500 hover:text-[#F7C264] transition"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="text-gray-500 hover:text-[#F7C264] transition"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
