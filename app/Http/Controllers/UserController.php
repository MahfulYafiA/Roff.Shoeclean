<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // =========================================================================
    // AREA PELANGGAN (DASHBOARD & RIWAYAT)
    // =========================================================================

    /**
     * Dashboard Pelanggan: Menampilkan ringkasan aktivitas
     */
    public function dashboard()
    {
        $user = Auth::user();

        // 🚨 GEMBOK ROLE: Mencegah salah masuk dashboard
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Mengambil 5 riwayat pesanan terbaru
        $riwayat = Reservasi::with(['detail.layanan', 'pembayaran'])
                    ->where('id_user', $user->id_user)
                    ->latest('id_reservasi') 
                    ->take(5) 
                    ->get();

        return view('pelanggan.dashboard', compact('riwayat'));
    }

    /**
     * Riwayat Pesanan: Menampilkan seluruh daftar transaksi pelanggan
     */
    public function riwayat()
    {
        $user = Auth::user();

        if ($user->role !== 'pelanggan') {
            return redirect()->route('dashboard');
        }

        $riwayat = Reservasi::with(['detail.layanan', 'pembayaran'])
                    ->where('id_user', $user->id_user)
                    ->latest('id_reservasi')
                    ->get();

        return view('pelanggan.reservasi.riwayat', compact('riwayat'));
    }


    // =========================================================================
    // AREA SUPERADMIN (MANAJEMEN USER)
    // =========================================================================

    /**
     * Menampilkan halaman Manajemen User & Statistik
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        // Statistik menggunakan ENUM sesuai database
        $totalAkun      = User::count();
        $totalStaf      = User::whereIn('role', ['superadmin', 'admin'])->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalNonaktif  = User::where('status', 'Nonaktif')->count();

        if (Auth::user()->role === 'superadmin') {
            return view('superadmin.users', compact('users', 'totalAkun', 'totalStaf', 'totalPelanggan', 'totalNonaktif'));
        }
        
        return view('admin.users', compact('users', 'totalAkun', 'totalStaf', 'totalPelanggan', 'totalNonaktif'));
    }

    /**
     * Menyimpan Admin / Staf Baru ke Database
     */
    public function store(Request $request)
    {
        // 🚨 SINKRONISASI: Menyesuaikan Max Character dengan Workbench
        $request->validate([
            'nama'     => 'required|string|max:40', // Sesuai VARCHAR(40)
            'email'    => 'required|email|max:50|unique:ms_user,email', // Sesuai VARCHAR(50)
            'no_telp'  => 'required|string|max:15', // Sesuai VARCHAR(15)
            'password' => 'required|min:8',
        ], [
            'nama.max' => 'Nama maksimal 40 karakter.',
            'no_telp.max' => 'Nomor telepon maksimal 15 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        try {
            User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'no_telp'  => $request->no_telp,
                'password' => Hash::make($request->password),
                'role'     => 'admin', 
                'status'   => 'Aktif', 
            ]);

            return redirect()->back()->with('success', 'Admin staf baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah staf.');
        }
    }

    /**
     * Fitur Saklar (Toggle) Aktif / Nonaktif Akun
     */
    public function toggle($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->id_user == Auth::user()->id_user) {
                return redirect()->back()->with('error', 'Anda tidak bisa menonaktifkan diri sendiri!');
            }

            // Toggle ENUM Status
            $user->status = ($user->status === 'Aktif') ? 'Nonaktif' : 'Aktif';
            $user->save();

            $statusText = $user->status === 'Aktif' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Akun {$user->nama} berhasil {$statusText}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status akun.');
        }
    }

    /**
     * Menghapus Akun Permanen
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->role === 'superadmin') {
                return redirect()->back()->with('error', 'Akun Superadmin tidak dapat dihapus!');
            }

            $user->delete();
            return redirect()->back()->with('success', 'Akun berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus akun. User ini mungkin memiliki riwayat transaksi.');
        }
    }
}