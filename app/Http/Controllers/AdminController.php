<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\User; 
use App\Models\DetailReservasi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; 

class AdminController extends Controller
{
    // ========================================================
    // 1. DASHBOARD AREA
    // ========================================================
    
    public function dashboard()
    {
        // Menghitung statistik untuk Dashboard Admin (Kasir)
        $totalAntrean = Reservasi::whereIn('status', ['Menunggu Konfirmasi', 'Diproses', 'Dicuci', 'Menunggu Kurir'])->count();
        $totalSelesai = Reservasi::where('status', 'Selesai')->count();

        return view('admin.dashboard', compact('totalAntrean', 'totalSelesai'));
    }

    public function superDashboard()
    {
        // Dashboard Superadmin (Owner)
        $users = User::all();
        return view('superadmin.dashboard', compact('users'));
    }

    // ========================================================
    // 2. MANAJEMEN USER (ADMIN SIDE - KASIR)
    // ========================================================

    public function users()
    {
        // Admin hanya bisa mengelola data pelanggan
        $users = User::where('role', 'pelanggan')->orderBy('created_at', 'desc')->get();
        $totalPelanggan = $users->count();

        return view('admin.users', compact('users', 'totalPelanggan'));
    }

    public function destroyUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            // Proteksi: Admin tidak bisa hapus sesama Admin atau Superadmin
            if ($user->role === 'admin' || $user->role === 'superadmin') {
                return redirect()->back()->with('error', 'Akses Ditolak! Anda tidak bisa menghapus akun staf/admin.');
            }

            // Hapus Foto Profil jika ada
            if ($user->foto_profil) { Storage::disk('public')->delete($user->foto_profil); }

            // Cari semua reservasi milik user ini untuk hapus bukti bayar
            $idReservasis = Reservasi::where('id_user', $id)->pluck('id_reservasi');
            foreach ($idReservasis as $idRes) {
                $pembayaran = Pembayaran::where('id_reservasi', $idRes)->first();
                if ($pembayaran && $pembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                }
            }

            $user->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus bersih.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    // ========================================================
    // 3. MANAJEMEN USER (SUPERADMIN / OWNER SIDE)
    // ========================================================

    public function superUsers()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('superadmin.users', compact('users'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:40',
            'email'    => 'required|email|unique:ms_user,email|max:50',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,pelanggan'
        ]);

        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->back()->with('success', 'Akun ' . strtoupper($request->role) . ' baru berhasil ditambahkan!');
    }

    public function destroySuperUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            if ($user->id_user === auth()->user()->id_user) {
                return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
            }

            if ($user->foto_profil) { Storage::disk('public')->delete($user->foto_profil); }

            $idReservasis = Reservasi::where('id_user', $id)->pluck('id_reservasi');
            foreach ($idReservasis as $idRes) {
                $pembayaran = Pembayaran::where('id_reservasi', $idRes)->first();
                if ($pembayaran && $pembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                }
            }

            $user->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Akun berhasil dihapus selamanya dari sistem.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    // ========================================================
    // 4. MONITOR ANTREAN & LAPORAN
    // ========================================================

    public function antrean()
    {
        // Load relasi detail.layanan agar tidak error saat dipanggil di Blade
        $semuaPesanan = Reservasi::with(['user', 'detail.layanan', 'pembayaran'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.antrean', compact('semuaPesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Konfirmasi,Diproses,Dicuci,Selesai,Menunggu Kurir'
        ]);

        try {
            $reservasi = Reservasi::findOrFail($id);
            $reservasi->status = $request->status;
            $reservasi->save();

            // Otomatis lunas jika status Selesai dan pilih Bayar di Toko
            if ($request->status == 'Selesai') {
                $pembayaran = Pembayaran::where('id_reservasi', $id)->first();
                if ($pembayaran && $pembayaran->metode_pembayaran == 'Bayar di Toko') {
                    $pembayaran->update([
                        'status_pembayaran' => 'Lunas',
                        'tanggal_bayar' => now()
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Status Pesanan #' . $id . ' Berhasil Diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Update Status: ' . $e->getMessage());
        }
    }

    public function superLaporan()
    {
        // Mengambil data reservasi yang sudah SELESAI saja untuk laporan omzet
        $laporanOmzet = Reservasi::with(['user', 'detail.layanan'])
                        ->where('status', 'Selesai')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $totalOmzet = $laporanOmzet->sum('total_harga');

        return view('superadmin.laporan', compact('laporanOmzet', 'totalOmzet'));
    }
}