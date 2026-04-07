<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\User; 
use App\Models\DetailReservasi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Tambahkan ini jika ingin menghapus foto bukti transfer

class AdminController extends Controller
{
    // ========================================================
    // 1. DASHBOARD AREA
    // ========================================================
    
    // Dashboard untuk Admin & Staff
    public function dashboard()
    {
        $totalAntrean = Reservasi::whereIn('status', ['Menunggu Konfirmasi', 'Diproses', 'Dicuci'])->count();
        $totalSelesai = Reservasi::where('status', 'Selesai')->count();

        return view('admin.dashboard', compact('totalAntrean', 'totalSelesai'));
    }

    // Dashboard untuk Superadmin (Owner)
    public function superDashboard()
    {
        $users = User::all();
        return view('superadmin.dashboard', compact('users'));
    }


    // ========================================================
    // 2. MANAJEMEN USER (ADMIN SIDE) - HANYA PELANGGAN
    // ========================================================

    // Tampilan Manajemen User untuk Admin
    public function users()
    {
        $users = User::whereNotIn('id_role', [1, 2])->orderBy('created_at', 'desc')->get();
        $totalPelanggan = $users->count();

        return view('admin.users', compact('users', 'totalPelanggan'));
    }

    // 🚨 UPDATE: Fungsi Hapus User + Cascade Delete Riwayat Pesanan
    public function destroyUser($id)
    {
        DB::beginTransaction(); // Memulai transaksi DB agar aman

        try {
            $user = User::findOrFail($id);
            
            // Proteksi Admin hapus Superadmin/Admin
            if (in_array($user->id_role, [1, 2])) {
                return redirect()->back()->with('error', 'Akses Ditolak! Anda hanya diizinkan untuk menghapus data Pelanggan.');
            }

            // 1. Cari semua ID Reservasi milik User ini
            $idReservasis = Reservasi::where('id_user', $id)->pluck('id_reservasi');

            if ($idReservasis->isNotEmpty()) {
                // Hapus Bukti Transfer (Fisik File) jika ada
                $pembayarans = Pembayaran::whereIn('id_reservasi', $idReservasis)->get();
                foreach ($pembayarans as $p) {
                    if ($p->bukti_pembayaran) {
                        Storage::disk('public')->delete($p->bukti_pembayaran);
                    }
                }

                // 2. Hapus Pembayaran & Detail (Child Tables)
                Pembayaran::whereIn('id_reservasi', $idReservasis)->delete();
                DetailReservasi::whereIn('id_reservasi', $idReservasis)->delete();
                
                // 3. Hapus Data Induk Reservasi
                Reservasi::whereIn('id_reservasi', $idReservasis)->delete();
            }

            // 4. Terakhir, Hapus User-nya
            $nama_user = $user->nama;
            $user->delete();

            DB::commit(); // Simpan semua perubahan
            return redirect()->back()->with('success', 'Pelanggan ' . $nama_user . ' beserta seluruh riwayat antreannya berhasil dihapus bersih!');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua hapus jika terjadi error di tengah jalan
            return redirect()->back()->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }


    // ========================================================
    // 3. MANAJEMEN & MONITOR ANTREAN
    // ========================================================

    public function antrean()
    {
        $semuaPesanan = Reservasi::with(['user', 'detail.layanan', 'pembayaran'])
                        ->orderBy('id_reservasi', 'desc')
                        ->get();

        return view('admin.antrean', compact('semuaPesanan'));
    }

    public function superAntrean()
    {
        $semuaPesanan = Reservasi::with(['user', 'detail.layanan', 'pembayaran'])
                        ->orderBy('id_reservasi', 'desc')
                        ->get();

        return view('superadmin.antrean', compact('semuaPesanan'));
    }

    public function superLaporan()
    {
        $laporanOmzet = Reservasi::with(['user', 'detail.layanan', 'pembayaran'])
                        ->where('status', 'Selesai')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $totalOmzet = $laporanOmzet->sum('total_harga');

        return view('superadmin.laporan', compact('laporanOmzet', 'totalOmzet'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Konfirmasi,Diproses,Dicuci,Selesai,Menunggu Kurir' // Tambahan Menunggu Kurir
        ]);

        try {
            $reservasi = Reservasi::findOrFail($id);
            $reservasi->status = $request->status;
            $reservasi->save();

            if ($request->status == 'Selesai') {
                $pembayaran = Pembayaran::where('id_reservasi', $id)->first();
                if ($pembayaran && $pembayaran->metode_pembayaran == 'Bayar di Toko') {
                    $pembayaran->status_pembayaran = 'Lunas';
                    $pembayaran->tanggal_bayar = now();
                    $pembayaran->save();
                }
            }

            return redirect()->back()->with('success', 'Status pesanan #' . $id . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $reservasi = Reservasi::findOrFail($id);
            
            // Hapus file bukti pembayaran jika ada
            $pembayaran = Pembayaran::where('id_reservasi', $id)->first();
            if ($pembayaran && $pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            }

            DetailReservasi::where('id_reservasi', $id)->delete();
            Pembayaran::where('id_reservasi', $id)->delete();
            $reservasi->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Data pesanan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }


    // ========================================================
    // 4. KELOLA HAK AKSES & USER (KHUSUS SUPERADMIN)
    // ========================================================

    public function superUsers()
    {
        $users = User::orderBy('id_role', 'asc')->orderBy('created_at', 'desc')->get();
        return view('superadmin.users', compact('users'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:ms_user,email',
            'password' => 'required|string|min:8',
            'no_telp'  => 'required|string|max:20',
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem!',
            'password.min' => 'Kata sandi minimal 8 karakter!'
        ]);

        try {
            User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => bcrypt($request->password), 
                'no_telp'  => $request->no_telp,
                'id_role'  => 2,
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan Admin baru bernama ' . $request->nama);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan admin: ' . $e->getMessage());
        }
    }

    // 🚨 UPDATE: Fungsi Hapus User oleh Superadmin + Cascade Delete
    public function destroySuperUser($id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            // PROTEKSI
            if ($user->id_user == auth()->user()->id_user) {
                return redirect()->back()->with('error', 'Akses Ditolak! Anda tidak bisa menghapus akun Anda sendiri.');
            }

            // 1. Cari semua ID Reservasi milik User ini
            $idReservasis = Reservasi::where('id_user', $id)->pluck('id_reservasi');

            if ($idReservasis->isNotEmpty()) {
                // Hapus Bukti Transfer
                $pembayarans = Pembayaran::whereIn('id_reservasi', $idReservasis)->get();
                foreach ($pembayarans as $p) {
                    if ($p->bukti_pembayaran) {
                        Storage::disk('public')->delete($p->bukti_pembayaran);
                    }
                }

                // 2. Hapus Child Tables
                Pembayaran::whereIn('id_reservasi', $idReservasis)->delete();
                DetailReservasi::whereIn('id_reservasi', $idReservasis)->delete();
                
                // 3. Hapus Induk Reservasi
                Reservasi::whereIn('id_reservasi', $idReservasis)->delete();
            }

            // 4. Hapus User
            $nama_user = $user->nama;
            $user->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Pengguna ' . $nama_user . ' dan seluruh datanya berhasil dihapus dari sistem.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}