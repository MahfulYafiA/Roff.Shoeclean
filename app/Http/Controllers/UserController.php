<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ==========================================
    // 1. FUNGSI UNTUK PELANGGAN (CUSTOMER)
    // ==========================================
    
    public function dashboard()
    {
        $user = auth()->user();

        // 🚨 GEMBOK 2: Cegat Admin & Superadmin jika nyasar ke dasbor pelanggan
        if ($user->id_role == 1) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->id_role == 2) {
            return redirect()->route('admin.dashboard');
        }

        // Mengambil data riwayat pesanan (dibatasi 5 terbaru untuk dasbor)
        $riwayat = Reservasi::with(['detail.layanan', 'pembayaran'])
                            ->where('id_user', $user->id_user)
                            ->orderBy('id_reservasi', 'desc')
                            ->take(5) 
                            ->get();

        // ✅ UPDATE PATH VIEW: Sekarang mengarah ke folder 'resources/views/pelanggan/dashboard.blade.php'
        return view('pelanggan.dashboard', compact('riwayat'));
    }

    public function riwayat()
    {
        $user = auth()->user();

        // 🚨 GEMBOK 2: Cegat Admin & Superadmin
        if ($user->id_role == 1) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->id_role == 2) {
            return redirect()->route('admin.dashboard');
        }

        // Mengambil semua data riwayat pesanan
        $riwayat = Reservasi::with(['detail.layanan', 'pembayaran'])
                    ->where('id_user', $user->id_user)
                    ->orderBy('id_reservasi', 'desc')
                    ->get();

        // ✅ UPDATE PATH VIEW: Mengarah ke folder 'resources/views/pelanggan/reservasi/riwayat.blade.php'
        return view('pelanggan.reservasi.riwayat', compact('riwayat'));
    }

    // ==========================================
    // Catatan Arsitektur (Bisa dijelaskan ke dosen):
    // Fungsi kelola user (index & destroy) untuk Superadmin telah dihapus dari controller ini.
    // Hal ini menerapkan prinsip YAGNI (You Aren't Gonna Need It) dan Single Responsibility,
    // karena Superadmin kini difokuskan khusus pada Laporan Omzet.
    // ==========================================
}