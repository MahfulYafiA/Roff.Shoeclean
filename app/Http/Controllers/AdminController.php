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
        $totalAntrean = Reservasi::whereIn('status', ['Diajukan', 'Diproses'])->count();
        $totalSelesai = Reservasi::where('status', 'Selesai')->count();

        return view('admin.dashboard', compact('totalAntrean', 'totalSelesai'));
    }

    public function superDashboard()
    {
        $users = User::all();
        return view('superadmin.dashboard', compact('users'));
    }

    // ========================================================
    // 2. MANAJEMEN USER (ADMIN SIDE - KASIR)
    // ========================================================

    public function users()
    {
        $users = User::where('id_role', 3)->orderBy('created_at', 'desc')->get();
        $totalPelanggan = $users->count();

        return view('admin.users', compact('users', 'totalPelanggan'));
    }

    public function destroyUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            if ($user->id_role === 1 || $user->id_role === 2) {
                return redirect()->back()->with('error', 'Akses Ditolak! Anda tidak bisa menghapus akun staf/admin.');
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

    /**
     * ✅ FITUR BARU: Toggle Status Aktif/Nonaktif User
     * Biar Superadmin bisa "pecat" sementara staf tanpa hapus data.
     */
    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            // Proteksi: Superadmin dilarang menonaktifkan dirinya sendiri
            if ($user->id_user === auth()->user()->id_user) {
                return redirect()->back()->with('error', 'Waduh Mas, jangan nonaktifkan akun sendiri! Nanti nggak bisa login lagi lho. 😂');
            }

            // Balikkan status: jika 1 jadi 0, jika 0 jadi 1
            $user->is_active = !$user->is_active;
            $user->save();

            $statusText = $user->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Akun {$user->nama} berhasil {$statusText}!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:40',
            'no_telp'  => 'required|string|max:20',
            'email'    => 'required|email|unique:ms_user,email|max:50',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama'      => $request->nama,
            'no_telp'   => $request->no_telp,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'id_role'   => 2, 
            'is_active' => true, // Default saat dibuat langsung aktif
        ]);

        return redirect()->back()->with('success', 'Akun ADMIN baru berhasil ditambahkan!');
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
    // 4. MONITOR ANTREAN, KURIR & LAPORAN
    // ========================================================

    public function antrean()
    {
        $semuaPesanan = Reservasi::with(['user', 'detail.layanan', 'pembayaran'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.antrean', compact('semuaPesanan'));
    }

    public function antreanKurir()
    {
        $antrean_kurir = Reservasi::with('user')
                            ->whereIn('status', ['Diproses'])
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return view('admin.kurir', compact('antrean_kurir'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diajukan,Diproses,Selesai,Batalkan'
        ]);

        try {
            $reservasi = Reservasi::findOrFail($id);
            $reservasi->status = $request->status;

            if ($request->status == 'Selesai' && $reservasi->status_bayar != 'Lunas') {
                $pembayaran = Pembayaran::where('id_reservasi', $id)->first();
                if ($pembayaran && in_array($pembayaran->metode_bayar, ['Bayar di Toko', 'Bayar di Kasir', 'Cash', 'COD'])) {
                    $reservasi->status_bayar = 'Lunas';
                    $pembayaran->update(['tanggal' => now()]);
                }
            }

            $reservasi->save();
            return redirect()->back()->with('success', 'Status Pesanan #' . $id . ' Berhasil Diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Update Status: ' . $e->getMessage());
        }
    }

    public function superLaporan()
    {
        $laporan = Reservasi::with(['user', 'detail.layanan'])
                        ->where('status', 'Selesai')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $totalOmzet = $laporan->sum('total_harga');

        return view('superadmin.laporan', compact('laporan', 'totalOmzet'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $reservasi = Reservasi::findOrFail($id);
            DetailReservasi::where('id_reservasi', $id)->delete();
            Pembayaran::where('id_reservasi', $id)->delete();
            $reservasi->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Data antrean berhasil dihapus beserta detailnya!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data antrean: ' . $e->getMessage());
        }
    }
}