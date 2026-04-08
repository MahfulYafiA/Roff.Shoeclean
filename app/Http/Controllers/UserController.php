<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Dashboard Pelanggan: Menampilkan ringkasan aktivitas
     */
    public function dashboard()
    {
        $user = auth()->user();

        // 🚨 GEMBOK ROLE: Redirect otomatis jika role tidak sesuai
        if ($user->id_role == 1) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->id_role == 2) {
            return redirect()->route('admin.dashboard');
        }

        // Mengambil 5 riwayat pesanan terbaru dengan Eager Loading
        $riwayat = Reservasi::with(['detail.layanan', 'pembayaran'])
                    ->where('id_user', $user->id_user)
                    ->latest('id_reservasi') // Lebih simpel daripada orderBy
                    ->take(5) 
                    ->get();

        return view('pelanggan.dashboard', compact('riwayat'));
    }

    /**
     * Riwayat Pesanan: Menampilkan seluruh daftar transaksi pelanggan
     */
    public function riwayat()
    {
        $user = auth()->user();

        // 🚨 GEMBOK ROLE
        if ($user->id_role == 1) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->id_role == 2) {
            return redirect()->route('admin.dashboard');
        }

        // Mengambil semua data riwayat pesanan
        $riwayat = Reservasi::with(['detail.layanan', 'pembayaran'])
                    ->where('id_user', $user->id_user)
                    ->latest('id_reservasi')
                    ->get();

        return view('pelanggan.reservasi.riwayat', compact('riwayat'));
    }
}