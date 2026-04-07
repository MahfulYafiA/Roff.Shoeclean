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
use App\Http\Controllers\PasswordResetController; // ✅ PANGGIL CONTROLLER BARU

// ==========================================
// 1. RUTE LANDING PAGE (Publik)
// ==========================================
Route::get('/', function () {
    $layanans = Schema::hasTable('ms_layanan') ? Layanan::all() : []; 
    return view('beranda.landing', compact('layanans'));
})->name('landing');

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
    
    // ✅ FITUR RESET PASSWORD VIA EMAIL
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');
});

// ==========================================
// 3. RUTE TERPROTEKSI (Wajib Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- AREA PROFIL (Dipakai Semua Role termasuk Superadmin) ---
    Route::prefix('profil')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profil.index');
        Route::patch('/update', [ProfileController::class, 'update'])->name('profil.update');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('profil.updatePassword');
        Route::patch('/foto', [ProfileController::class, 'updateFoto'])->name('profil.updateFoto');
        Route::delete('/foto', [ProfileController::class, 'hapusFoto'])->name('profil.hapusFoto');
    });

    // --- AREA RESERVASI (PELANGGAN) ---
    Route::prefix('reservasi')->group(function () {
        Route::get('/baru', [ReservasiController::class, 'create'])->name('reservasi.create');
        Route::post('/baru', [ReservasiController::class, 'store'])->name('reservasi.store');
        Route::get('/riwayat', [UserController::class, 'riwayat'])->name('reservasi.riwayat');
        Route::get('/pembayaran/{id}', [ReservasiController::class, 'pembayaran'])->name('reservasi.pembayaran');
        Route::post('/pilih-pengembalian/{id}', [ReservasiController::class, 'pilihPengembalian'])->name('reservasi.pilih-pengembalian');
    });

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // ==========================================
    // --- AREA ADMIN & STAFF (FULL FITUR) ---
    // ==========================================
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::get('/antrean', [AdminController::class, 'antrean'])->name('admin.antrean');
        Route::post('/reservasi/update/{id}', [AdminController::class, 'updateStatus'])->name('admin.reservasi.update');
        Route::delete('/reservasi/delete/{id}', [AdminController::class, 'destroy'])->name('admin.reservasi.destroy');
        
        // --- FITUR KELOLA LAYANAN (FULL CRUD) ---
        Route::get('/layanan', [LayananController::class, 'index'])->name('admin.layanan');
        Route::post('/layanan', [LayananController::class, 'store'])->name('admin.layanan.store');
        Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('admin.layanan.update');
        Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('admin.layanan.destroy');
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    });

    // ==========================================
    // --- AREA SUPERADMIN (OWNER - RAMPING) ---
    // ==========================================
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'superDashboard'])->name('superadmin.dashboard');
        Route::get('/laporan', [AdminController::class, 'superLaporan'])->name('superadmin.laporan');
        Route::get('/users', [AdminController::class, 'superUsers'])->name('superadmin.users');
        Route::post('/users', [AdminController::class, 'storeAdmin'])->name('superadmin.users.store');
        Route::delete('/users/{id}', [AdminController::class, 'destroySuperUser'])->name('superadmin.users.destroy');
    });
});