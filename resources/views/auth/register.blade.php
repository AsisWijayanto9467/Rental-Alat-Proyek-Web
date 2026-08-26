@extends('layouts.auth')

@section('title', 'Register - RENTAL PRO')

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
                Join<br>
                <span class="text-[#F7C264]">Rental Pro</span><br>
                Today
            </h2>
            <p class="text-gray-400 text-lg leading-relaxed max-w-md">
                Create an account to start renting premium construction equipment for your projects.
            </p>
        </div>
    </div>

    {{-- Right Side - Register Form --}}
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
                    <h2 class="text-2xl font-extrabold text-[#2A2A2A]">Buat Akun Baru</h2>
                    <p class="text-gray-500 mt-1">Isi data diri Anda untuk mendaftar</p>
                </div>

                <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                    @csrf

                    @if(request('redirect'))
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif

                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('nama') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('username') border-red-500 @enderror"
                            placeholder="Masukkan username">
                        @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('email') border-red-500 @enderror"
                            placeholder="Masukkan email">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition @error('password') border-red-500 @enderror"
                                placeholder="Minimal 6 karakter">
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition"
                                placeholder="Ulangi password">
                        </div>
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-1.5">No. Telepon <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea id="alamat" name="alamat" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#F7C264] focus:border-[#F7C264] transition resize-none"
                            placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-[#2A2A2A] hover:bg-[#1a1a1a] text-white font-bold py-3 rounded-xl transition text-sm mt-2">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-500 text-sm">Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-[#2A2A2A] hover:text-[#F7C264] font-bold transition">Masuk</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
