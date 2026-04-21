<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Models\DetailReservasi;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTransaksiController extends Controller
{
    /**
     * Menampilkan form kasir offline
     */
    public function createOffline()
    {
        $layanans = Layanan::all();
        return view('admin.transaksi.offline', compact('layanans'));
    }

    /**
     * Memproses penyimpanan transaksi offline
     */
    public function storeOffline(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:40',
            'no_telp'        => 'nullable|string|max:15',
            'id_layanan'     => 'required|exists:ms_layanan,id_layanan',
            'jumlah'         => 'required|integer|min:1',
            'metode_bayar'   => 'required|in:Tunai,Transfer Manual',
            'status_bayar'   => 'required|in:Lunas,Belum Lunas',
        ]);

        DB::beginTransaction();

        try {
            // 1. CARI ATAU BUAT AKUN DUMMY UNTUK PELANGGAN OFFLINE
            // Agar strukturnya rapi, kita tetap butuh id_user. Kita buat akun "Walk-in"
            $user_offline = User::firstOrCreate(
                ['email' => 'offline_' . time() . '@roff.com'], // Email unik dummy
                [
                    'nama'     => $request->nama_pelanggan . ' (Offline)',
                    'password' => Hash::make('rahasia'), // Password acak
                    'id_role'  => 3,
                    'no_telp'  => $request->no_telp,
                ]
            );

            $layanan = Layanan::findOrFail($request->id_layanan);
            $sub_total = $layanan->harga * $request->jumlah;

            // 2. SIMPAN RESERVASI
            $reservasi = Reservasi::create([
                'id_user'             => $user_offline->id_user,
                'tanggal_reservasi'   => now()->toDateString(),
                'metode_layanan'      => 'Drop-off', // Offline pasti Drop-off
                'status'              => 'Diproses', // Langsung diproses karena sudah di toko
                'status_bayar'        => $request->status_bayar,
                'total_harga'         => $sub_total,
                'alamat_jemput'       => null,
            ]);

            $id_res_baru = $reservasi->id_reservasi ?? $reservasi->id;

            // 3. SIMPAN DETAIL
            DetailReservasi::create([
                'id_reservasi' => $id_res_baru,
                'id_layanan'   => $request->id_layanan,
                'harga'        => $layanan->harga,
                'jumlah'       => $request->jumlah,
                'sub_total'    => $sub_total,
            ]);

            // 4. SIMPAN PEMBAYARAN
            Pembayaran::create([
                'id_reservasi'  => $id_res_baru,
                'tanggal'       => ($request->status_bayar == 'Lunas') ? now() : null,
                'jumlah'        => ($request->status_bayar == 'Lunas') ? $sub_total : null,
                'metode_bayar'  => $request->metode_bayar,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi Offline berhasil dicatat! (Atas Nama: ' . $request->nama_pelanggan . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Gagal menyimpan transaksi offline: ' . $e->getMessage()])->withInput();
        }
    }
}