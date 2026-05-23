<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\User; 
use App\Models\DetailReservasi;
// Model Pembayaran sudah dihapus dari sini
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ========================================================
    // 1. DASHBOARD AREA
    // ========================================================
    
    public function dashboard()
    {
        // ✨ SINKRON: Menggunakan lowercase sesuai ENUM database
        $totalAntrean = Reservasi::whereIn('status', ['diajukan', 'diproses'])->count();
        $totalSelesai = Reservasi::where('status', 'selesai')->count();

        return view('admin.dashboard', compact('totalAntrean', 'totalSelesai'));
    }

    public function superDashboard()
    {
        $totalOmzet = Reservasi::where('status', 'selesai')->sum('total_harga');
        $totalUser = User::count();
        $pesananTerbaru = Reservasi::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('superadmin.dashboard', compact('totalOmzet', 'totalUser', 'pesananTerbaru'));
    }

    // ========================================================
    // 2. MANAJEMEN USER (ADMIN SIDE - KASIR)
    // ========================================================

    public function users()
    {
        $users = User::where('role', 'pelanggan')->orderBy('created_at', 'desc')->get();
        $totalPelanggan = $users->count();

        return view('admin.users', compact('users', 'totalPelanggan'));
    }

    public function destroyUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            if ($user->role === 'superadmin' || $user->role === 'admin') {
                return redirect()->back()->with('error', 'Akses Ditolak! Anda tidak bisa menghapus akun staf/admin.');
            }

            if ($user->foto_profil) { 
                Storage::disk('public')->delete($user->foto_profil); 
            }

            // Kodingan hapus bukti_pembayaran dihilangkan karena fiturnya sudah diganti Midtrans/Otomatis

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

        $totalAkun = $users->count();
        $totalStaf = $users->whereIn('role', ['superadmin', 'admin'])->count();
        $totalPelanggan = $users->where('role', 'pelanggan')->count();
        $totalNonaktif = $users->where('status', 'Nonaktif')->count();

        return view('superadmin.users', compact(
            'users', 
            'totalAkun', 
            'totalStaf', 
            'totalPelanggan', 
            'totalNonaktif'
        ));
    }

    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id_user === Auth::user()->id_user) {
                return redirect()->back()->with('error', 'Anda tidak bisa menonaktifkan akun sendiri.');
            }

            $user->status = ($user->status === 'Aktif') ? 'Nonaktif' : 'Aktif';
            $user->save();

            $statusText = $user->status === 'Aktif' ? 'diaktifkan kembali' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Akun {$user->nama} berhasil {$statusText}!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status.');
        }
    }

    public function storeAdmin(Request $request)
    {
        // ✨ SINKRON: Mengetatkan validasi karakter sesuai Workbench
        $request->validate([
            'nama'     => 'required|string|max:40', // Sesuai VARCHAR(40)
            'no_telp'  => 'required|string|max:15', // Sesuai VARCHAR(15)
            'email'    => 'required|email|unique:ms_user,email|max:50', // Sesuai VARCHAR(50)
            'password' => 'required|min:8',
        ]);

        User::create([
            'nama'      => $request->nama,
            'no_telp'   => $request->no_telp,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'admin',  
            'status'    => 'Aktif',  
        ]);

        return redirect()->back()->with('success', 'Akun ADMIN baru berhasil ditambahkan!');
    }

    public function destroySuperUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            if ($user->id_user === Auth::user()->id_user) {
                return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
            }

            if ($user->foto_profil) { 
                Storage::disk('public')->delete($user->foto_profil); 
            }

            $user->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Akun berhasil dihapus selamanya dari sistem.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hapus.');
        }
    }

    // ========================================================
    // 4. MONITOR ANTREAN & LAPORAN
    // ========================================================

    public function antrean()
    {
        // Relasi 'pembayaran' SUDAH DIHAPUS DARI SINI
        $semuaPesanan = Reservasi::with(['user', 'detail.layanan'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.antrean', compact('semuaPesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        // ✨ SINKRON: Menggunakan lowercase sesuai ENUM image_9c097c.png
        $request->validate([
            'status' => 'required|in:diajukan,diproses,selesai,batalkan'
        ]);

        try {
            $reservasi = Reservasi::findOrFail($id);
            $reservasi->status = $request->status;

            // LOGIKA BARU: Update status Lunas langsung di tabel tr_reservasi
            if ($request->status == 'selesai' && $reservasi->status_bayar != 'Lunas') {
                if (in_array($reservasi->metode_bayar, ['Bayar di Toko', 'Cash', 'COD', 'Tunai', 'Transfer Manual', 'Transfer Bank'])) {
                    $reservasi->status_bayar = 'Lunas';
                    $reservasi->tanggal_bayar = now(); // Langsung isi tanggal bayar di tabel reservasi
                }
            }

            $reservasi->save();
            return redirect()->back()->with('success', 'Status Pesanan #' . $id . ' Berhasil Diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Update Status: ' . $e->getMessage());
        }
    }

    // ========================================================
    // ✨ FIX: SEKARANG SUPERADMIN SUDAH BISA FILTER TANGGAL
    // ========================================================
    public function superLaporan(Request $request)
    {
        // 1. Ambil input filter tanggal dari form. Jika kosong, set otomatis ke HARI INI
        $tgl_mulai = $request->get('tgl_mulai', now()->toDateString());
        $tgl_selesai = $request->get('tgl_selesai', now()->toDateString());

        // 2. Query Data dengan tambahan klausa filter rentang tanggal
        $laporan = Reservasi::with(['user', 'detail.layanan'])
                        ->where('status', 'selesai')
                        ->whereBetween('tanggal_reservasi', [$tgl_mulai, $tgl_selesai])
                        ->orderBy('updated_at', 'desc')
                        ->get();

        // 3. Hitung total omzet dari data yang berhasil difilter
        $totalOmzet = $laporan->sum('total_harga');

        // 4. Kirim seluruh variabel ke view superadmin.laporan
        return view('superadmin.laporan', compact('laporan', 'totalOmzet', 'tgl_mulai', 'tgl_selesai'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $reservasi = Reservasi::findOrFail($id);
            DetailReservasi::where('id_reservasi', $id)->delete();
            // Penghapusan tabel Pembayaran dimatikan karena tabelnya sudah tidak ada
            
            $reservasi->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Data antrean berhasil dihapus beserta detailnya!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}