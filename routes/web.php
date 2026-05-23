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
use App\Http\Controllers\DashboardController;

// ==========================================
// 1. RUTE LANDING PAGE (Publik)
// ==========================================
Route::get('/', function () {
    $layanans = Schema::hasColumn('ms_layanan', 'status') ? Layanan::where('status', 'Aktif')->get() : [];
    return view('beranda.landing', compact('layanans'));
})->name('landing');

// Webhook Midtrans (Pembayaran Otomatis)
Route::post('/midtrans/callback', [ReservasiController::class, 'callback'])->name('midtrans.callback');

// ==========================================
// 2. RUTE GUEST (Belum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Login Google & Reset Password
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');
});

// ==========================================
// 3. RUTE TERPROTEKSI (Sudah Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Switcher otomatis berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ✨ RUTE UNIVERSAL (Satu nama rute untuk semua Dashboard)
    Route::post('/update-hero-banner', [LayananController::class, 'updateHero'])->name('update.hero.universal');
    Route::post('/update-tentang-kami', [LayananController::class, 'updateTentang'])->name('update.tentang.universal');
    
    // Rute Toggle Status Layanan (Universal) - Pastikan menggunakan PATCH
    Route::patch('/layanan/toggle/{id}', [LayananController::class, 'toggleStatus'])->name('layanan.toggle');

    // Pengaturan Profil User
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
        Route::patch('/foto', [ProfileController::class, 'updateFoto'])->name('updateFoto');
        Route::delete('/foto', [ProfileController::class, 'hapusFoto'])->name('hapusFoto');
    });

    // Area Pelanggan (Reservasi)
    Route::prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/baru', [ReservasiController::class, 'create'])->name('create');
        Route::post('/baru', [ReservasiController::class, 'store'])->name('store');
        Route::get('/riwayat', [UserController::class, 'riwayat'])->name('riwayat');
        Route::get('/pembayaran/{id}', [ReservasiController::class, 'pembayaran'])->name('pembayaran');
    });

    // ✨ RUTE AJAX POLLING REAL-TIME (Memantau Perubahan Status Tanpa Refresh)
    Route::get('/cek-status-realtime', [ReservasiController::class, 'cekStatusTerbaru'])->name('cek.status.realtime');

    // ==========================================
    // 4. AREA ADMIN / STAF KASIR
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Rute Antrean & Hapus Reservasi
        Route::get('/antrean', [AdminController::class, 'antrean'])->name('antrean');
        Route::post('/reservasi/update/{id}', [AdminController::class, 'updateStatus'])->name('reservasi.update');
        Route::delete('/reservasi/delete/{id}', [AdminController::class, 'destroy'])->name('reservasi.destroy');
        
        // Rute User Pelanggan & Hapus User
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Manajemen Layanan (Admin Side)
        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/kasir-offline', [AdminTransaksiController::class, 'createOffline'])->name('transaksi.offline');
        Route::post('/kasir-offline', [AdminTransaksiController::class, 'storeOffline'])->name('transaksi.store-offline');
    });

    // ==========================================
    // 5. AREA SUPERADMIN / OWNER
    // ==========================================
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'superDashboard'])->name('dashboard');
        Route::get('/laporan', [AdminController::class, 'superLaporan'])->name('laporan');
        
        // Manajemen User & Staf (Khusus Owner)
        Route::get('/users', [AdminController::class, 'superUsers'])->name('users');
        Route::post('/users', [AdminController::class, 'storeAdmin'])->name('users.store');
        Route::delete('/users/{id}', [AdminController::class, 'destroySuperUser'])->name('users.destroy');
        Route::patch('/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        
        // ✨ INI JEMBATAN BARUNYA UNTUK FITUR EDIT USER
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

        // Manajemen Katalog Layanan (Superadmin Side)
        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
    });
});