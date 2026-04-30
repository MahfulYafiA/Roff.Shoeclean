<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data user yang sedang login
        $user = auth()->user();

        // ✨ UPDATE: Logika pengalihan menggunakan string ENUM (role)
        if ($user->role === 'superadmin') {
            // Role: superadmin -> Diarahkan ke rute superadmin
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'admin') {
            // Role: admin -> Diarahkan ke rute admin / staf kasir
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pelanggan') {
            // Role: pelanggan -> Menampilkan view dashboard pelanggan
            // Sesuai catatan Mas: file dashboard ada langsung di folder 'pelanggan'
            return view('pelanggan.dashboard');
        }

        // Jika role tidak dikenal (fail-safe), lempar ke landing page
        return redirect()->route('landing');
    }
}