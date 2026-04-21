<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Models\Layanan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminTransaksiController; 

// ==========================================
// 1. RUTE LANDING PAGE (Publik)
// ==========================================
Route::get('/', function () {
    $layanans = Schema::hasTable('ms_layanan') ? Layanan::where('is_active', true)->get() : []; 
    return view('beranda.landing', compact('layanans'));
})->name('landing');

// Webhook Midtrans
Route::post('/midtrans/callback', [ReservasiController::class, 'callback'])->name('midtrans.callback');

// ==========================================
// 2. RUTE AUTENTIKASI (Guest)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);
    
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');
});

// ==========================================
// 3. RUTE TERPROTEKSI (Auth)
// ==========================================
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/update-hero-banner', [LayananController::class, 'updateHero'])->name('update.hero.universal');
    Route::post('/update-tentang-kami', [LayananController::class, 'updateTentang'])->name('update.tentang.universal');

    // Profil
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
        Route::patch('/foto', [ProfileController::class, 'updateFoto'])->name('updateFoto');
        Route::delete('/foto', [ProfileController::class, 'hapusFoto'])->name('hapusFoto');
    });

    // Dashboard Switcher
    Route::get('/dashboard', function () {
        $role = auth()->user()->id_role; 
        if ($role == 1) return redirect()->route('superadmin.dashboard');
        if ($role == 2) return redirect()->route('admin.dashboard');
        return app(UserController::class)->dashboard();
    })->name('dashboard');

    // Pelanggan
    Route::prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/baru', [ReservasiController::class, 'create'])->name('create');
        Route::post('/baru', [ReservasiController::class, 'store'])->name('store');
        Route::get('/riwayat', [UserController::class, 'riwayat'])->name('riwayat');
        Route::get('/pembayaran/{id}', [ReservasiController::class, 'pembayaran'])->name('pembayaran');
        Route::post('/pilih-pengembalian/{id}', [ReservasiController::class, 'pilihPengembalian'])->name('pilih-pengembalian');
    });

    // ==========================================
    // 4. AREA ADMIN
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/antrean', [AdminController::class, 'antrean'])->name('antrean');
        Route::post('/reservasi/update/{id}', [AdminController::class, 'updateStatus'])->name('reservasi.update');
        Route::delete('/reservasi/delete/{id}', [AdminController::class, 'destroy'])->name('reservasi.destroy');
        
        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
        Route::post('/layanan/{id}/toggle', [LayananController::class, 'toggleStatus'])->name('layanan.toggle');
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/kasir-offline', [AdminTransaksiController::class, 'createOffline'])->name('transaksi.offline');
        Route::post('/kasir-offline', [AdminTransaksiController::class, 'storeOffline'])->name('transaksi.store-offline');
    });

    // ==========================================
    // 5. AREA SUPERADMIN
    // ==========================================
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'superDashboard'])->name('dashboard');
        Route::get('/laporan', [AdminController::class, 'superLaporan'])->name('laporan');
        
        // Manajemen User
        Route::get('/users', [AdminController::class, 'superUsers'])->name('users');
        Route::post('/users', [AdminController::class, 'storeAdmin'])->name('users.store');
        Route::delete('/users/{id}', [AdminController::class, 'destroySuperUser'])->name('users.destroy');
        Route::patch('/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');

        // Layanan (DIBERSIHKAN DARI NAMA DOUBLE)
        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
        
        // 🚨 FIX: Cukup tulis 'layanan.toggle', nanti otomatis jadi 'superadmin.layanan.toggle'
        Route::post('/layanan/{id}/toggle', [LayananController::class, 'toggleStatus'])->name('layanan.toggle'); 
    });
});