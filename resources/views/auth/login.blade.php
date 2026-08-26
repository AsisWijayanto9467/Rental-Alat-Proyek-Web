@extends('layouts.auth')

@section('title', 'Login - RENTAL PRO')

@section('content')
<div class="min-h-screen flex">
    {{-- Left Side - Branding --}}
    <div class="hidden lg:flex lg:w-1/2 bg-[#2A2A2A] relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-20 w-72 h-72 bg-[#F7C264] rounded-full blur-[120px]"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-[#F7C264] rounded-full blur-[100px]"></div>
        </div>
        <div class="relative z-10 flex flex-col justify-center px-16 text-white">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-[#F7C264] rounded-xl flex items-center justify-center">
                    <i class="fas fa-hard-hat text-[#2A2A2A] text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight">RENTAL PRO</h1>
                    <p class="text-gray-400 text-sm font-medium">Construction Equipment</p>
                </div>
            </div>
            <h2 class="text-4xl font-extrabold leading-tight mb-4">
                Welcome Back to<br>
                <span class="text-[#F7C264]">Your Equipment</span><br>
                Rental Portal
            </h2>
            <p class="text-gray-400 text-lg leading-relaxed max-w-md">
                Access your rental dashboard, manage payments, and track your equipment rentals.
            </p>
            <div class="mt-10 grid grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-[#F7C264]">150+</div>
                    <div class="text-gray-500 text-xs mt-1">Units</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-[#F7C264]">3</div>
                    <div class="text-gray-500 text-xs mt-1">Locations</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-[#F7C264]">500+</div>
                    <div class="text-gray-500 text-xs mt-1">Projects</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side - Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-[#F7C264] rounded-lg flex items-center justify-center">
                    <i class="fas fa-hard-hat text-[#2A2A2A]"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-[#2A2A2A]">RENTAL PRO</h1>
                    <p class="text-gray-500 text-xs">Construction Equipment</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-extrabold text-[#2A2A2A]">Selamat Datang</h2>
                    <p class="text-gray-500 mt-1">Masuk ke akun Anda</p>
                </div>

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if(session('status'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    @if(request('redirect'))
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('username') border-red-500 @enderror"
                                placeholder="Masukkan username">
                        </div>
                        @error('username')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input :type="show ? 'text' : 'password'" id="password" name="password" required
                                class="w-full pl-11 pr-12 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-[#F7C264] border-gray-300 rounded focus:ring-[#F7C264]">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-[#2A2A2A] hover:bg-[#1a1a1a] text-white font-bold py-3 rounded-xl transition text-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-500 text-sm">Belum punya akun?
                        <a href="{{ route('register') }}" class="text-[#2A2A2A] hover:text-[#F7C264] font-bold transition">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
