@extends('layouts.app')

@section('title', 'RENTAL PRO - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <div class="w-16 h-16 bg-brand-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-shield text-white text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900">Panel Admin</h2>
        <p class="text-gray-500 mt-2">Halo, {{ auth()->user()->nama }}</p>
        <p class="text-gray-400 text-sm mt-4">Panel admin akan segera tersedia.</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-xl font-semibold transition">
                Keluar
            </button>
        </form>
    </div>
</div>
@endsection
