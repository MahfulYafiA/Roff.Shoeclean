<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $user = auth()->user();

        // 🚨 GEMBOK ROLE: Pengecekan disesuaikan dengan kolom 'role' (teks)
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'admin') {
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

        // 🚨 GEMBOK ROLE: Pengecekan disesuaikan dengan kolom 'role' (teks)
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Mengambil semua data riwayat pesanan
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
        // Ambil semua data user urut dari yang paling baru daftar
        $users = User::orderBy('created_at', 'desc')->get();

        // ✨ PERBAIKAN LOGIKA STATISTIK (MENGGUNAKAN ENUM)
        $totalAkun      = User::count();
        $totalStaf      = User::whereIn('role', ['superadmin', 'admin'])->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalNonaktif  = User::where('status', 'Nonaktif')->count();

        // Pengecekan view untuk superadmin/admin
        if (auth()->user()->role === 'superadmin') {
            return view('superadmin.users', compact('users', 'totalAkun', 'totalStaf', 'totalPelanggan', 'totalNonaktif'));
        }
        
        return view('admin.users', compact('users', 'totalAkun', 'totalStaf', 'totalPelanggan', 'totalNonaktif'));
    }

    /**
     * Menyimpan Admin / Staf Baru ke Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:ms_user,email', // Pastikan tabel ms_user
            'no_telp'  => 'required|string|max:20',
            'password' => 'required|min:8',
        ]);

        try {
            User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'no_telp'  => $request->no_telp,
                'password' => Hash::make($request->password), // Enkripsi password
                'role'     => 'admin',  // ✨ BUKAN id_role lagi
                'status'   => 'Aktif',  // ✨ BUKAN is_active lagi
            ]);

            return redirect()->back()->with('success', 'Admin staf baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah staf: ' . $e->getMessage());
        }
    }

    /**
     * Fitur Saklar (Toggle) Aktif / Nonaktif Akun
     */
    public function toggle($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Mencegah superadmin menonaktifkan dirinya sendiri
            if ($user->id_user == auth()->user()->id_user) {
                return redirect()->back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri!');
            }

            // ✨ PERBAIKAN LOGIKA STATUS ENUM
            if ($user->status === 'Aktif') {
                $user->status = 'Nonaktif';
            } else {
                $user->status = 'Aktif';
            }
            
            $user->save();

            $msg = $user->status === 'Aktif' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Akun {$user->nama} berhasil {$msg}.");
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
            
            // Keamanan: Akun Superadmin tidak boleh dihapus sembarangan
            if ($user->role === 'superadmin') {
                return redirect()->back()->with('error', 'Keamanan Sistem: Akun Superadmin tidak dapat dihapus!');
            }

            $user->delete();
            return redirect()->back()->with('success', 'Akun berhasil dihapus permanen.');
        } catch (\Exception $e) {
            // Error ini biasanya muncul kalau akun sudah punya transaksi di tr_reservasi (Foreign Key)
            return redirect()->back()->with('error', 'Gagal menghapus akun. Pastikan akun tidak memiliki riwayat transaksi.');
        }
    }
}