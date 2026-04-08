<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\User; 
use App\Models\DetailReservasi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ========================================================
    // 1. DASHBOARD AREA
    // ========================================================
    
    public function dashboard()
    {
        // Menghitung antrean aktif (Status selain Selesai)
        $totalAntrean = Reservasi::whereIn('status', ['Menunggu Konfirmasi', 'Diproses', 'Dicuci', 'Menunggu Kurir'])->count();
        $totalSelesai = Reservasi::where('status', 'Selesai')->count();

        return view('admin.dashboard', compact('totalAntrean', 'totalSelesai'));
    }

    public function superDashboard()
    {
        // Menampilkan semua user untuk Owner
        $users = User::all();
        return view('superadmin.dashboard', compact('users'));
    }

    // ========================================================
    // 2. MANAJEMEN USER (ADMIN SIDE)
    // ========================================================

    public function users()
    {
        // Menampilkan selain Superadmin(1) dan Admin(2)
        $users = User::where('id_role', '>', 2)->orderBy('created_at', 'desc')->get();
        $totalPelanggan = $users->count();

        return view('admin.users', compact('users', 'totalPelanggan'));
    }

    public function destroyUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            // Proteksi: Admin tidak boleh hapus sesama Admin atau Superadmin
            if ($user->id_role <= 2) {
                return redirect()->back()->with('error', 'Akses Ditolak! Anda tidak bisa menghapus akun staf/admin.');
            }

            // Hapus File Fisik (Foto Profil & Bukti Bayar)
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $idReservasis = Reservasi::where('id_user', $id)->pluck('id_reservasi');
            foreach ($idReservasis as $idRes) {
                $pembayaran = Pembayaran::where('id_reservasi', $idRes)->first();
                if ($pembayaran && $pembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                }
            }

            // Record DB akan terhapus otomatis karena 'cascade' di Migration kita
            $user->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus bersih.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    // ========================================================
    // 3. MONITOR ANTREAN & LAPORAN
    // ========================================================

    public function antrean()
    {
        // Eager Loading agar query ringan (Optimasi Strict)
        $semuaPesanan = Reservasi::with(['user', 'layanan', 'pembayaran'])
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

            // Automasi: Jika Selesai dan Bayar di Toko, set Lunas
            if ($request->status == 'Selesai') {
                $pembayaran = Pembayaran::where('id_reservasi', $id)->first();
                if ($pembayaran && $pembayaran->metode_pembayaran == 'Bayar di Toko') {
                    $pembayaran->update([
                        'status_pembayaran' => 'Lunas',
                        'tanggal_bayar' => now()
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Status updated!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function superLaporan()
    {
        $laporanOmzet = Reservasi::with(['user', 'layanan'])
                        ->where('status', 'Selesai')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $totalOmzet = $laporanOmzet->sum('total_harga');

        return view('superadmin.laporan', compact('laporanOmzet', 'totalOmzet'));
    }
}