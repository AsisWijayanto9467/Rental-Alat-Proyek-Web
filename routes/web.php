<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CrossStorageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Gambar dari shared storage (D:\Cross_Storage\Sistem_Proyek).
Route::get('storage/cross/{path}', [CrossStorageController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.cross');

Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
Route::get('/equipment/{id}', [EquipmentController::class, 'show'])->name('equipment.show');

Route::get('/how-it-works', [StaticController::class, 'howItWorks'])->name('how-it-works');
Route::get('/about', [StaticController::class, 'about'])->name('about');
Route::get('/contact', [StaticController::class, 'contact'])->name('contact');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Customer Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/my-rentals', [CustomerController::class, 'myRentals'])->name('customer.my-rentals');
    Route::get('/my-rentals/{id}', [CustomerController::class, 'rentalDetail'])->name('customer.rental-detail');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    Route::get('/rental/create/{id}', [RentalController::class, 'create'])->name('rental.create');
    Route::post('/rental', [RentalController::class, 'store'])->name('rental.store');
    Route::post('/rental/{id}/batal', [RentalController::class, 'batal'])->name('rental.batal');

    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/payment-denda/{penyewaan}/{denda}', [PaymentController::class, 'showDenda'])->name('payment.denda.show');

    Route::get('/pengembalian/{id}/create', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/pengembalian/{id}', [PengembalianController::class, 'store'])->name('pengembalian.store');
});

// Backend verifikasi untuk Admin/Petugas (UI lengkap dipisah ke aplikasi admin).
// Endpoint di bawah ini hanya menyediakan aksi backend agar alur user berjalan.
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    Route::get('/admin', [VerifikasiController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/petugas', [VerifikasiController::class, 'dashboard'])->name('petugas.dashboard');

    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/', [VerifikasiController::class, 'dashboard'])->name('dashboard');
        Route::post('/penyewaan/{id}/setujui', [VerifikasiController::class, 'setujuiPenyewaan'])->name('penyewaan.setujui');
        Route::post('/penyewaan/{id}/tolak', [VerifikasiController::class, 'tolakPenyewaan'])->name('penyewaan.tolak');
        Route::post('/pembayaran/{id}/verifikasi', [VerifikasiController::class, 'verifikasiPembayaran'])->name('pembayaran.verifikasi');
        Route::post('/pembayaran/{id}/tolak', [VerifikasiController::class, 'tolakPembayaran'])->name('pembayaran.tolak');
        Route::post('/pengembalian/{id}/terima', [VerifikasiController::class, 'terimaPengembalian'])->name('pengembalian.terima');
        Route::post('/pengembalian/{id}/tolak', [VerifikasiController::class, 'tolakPengembalian'])->name('pengembalian.tolak');
    });
});
