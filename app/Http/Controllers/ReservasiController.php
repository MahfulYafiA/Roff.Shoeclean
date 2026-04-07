<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Models\DetailReservasi;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReservasiController extends Controller
{
    /**
     * Menampilkan Form Reservasi (FR07)
     */
    public function create()
    {
        $layanans = Layanan::all(); 
        $user = auth()->user();
        
        return view('pelanggan.reservasi.create', compact('layanans', 'user'));
    }

    /**
     * Menampilkan Form Pembayaran (Jika ada)
     */
    public function pembayaran($id)
    {
        $reservasi = Reservasi::where('id_reservasi', $id)
                    ->where('id_user', auth()->user()->id_user)
                    ->firstOrFail();

        return view('pelanggan.reservasi.pembayaran', compact('reservasi'));
    }

    /**
     * Fungsi untuk menyimpan data reservasi awal
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan'        => 'required|exists:ms_layanan,id_layanan',
            'jumlah_sepatu'     => 'required|integer|min:1',
            'metode_layanan'    => 'required|in:Drop-off,Pick-up', // Harus Antar ke Toko atau Dijemput Kurir
            'metode_pembayaran' => 'required',
            'bukti_pembayaran'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alamat_jemput'     => 'nullable|string', 
            'no_telp'           => 'nullable|string|max:20', // 🚨 UPDATE: Tangkap nomor telepon dari form reservasi
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user(); // Ambil data user yang sedang login
            $layanan = Layanan::findOrFail($request->id_layanan);
            $total_harga = $layanan->harga * $request->jumlah_sepatu;

            // 🚨 SKENARIO OTOMATIS: Update profil user agar nomor WA dari akun Google langsung tersimpan permanen
            $user->update([
                'no_telp' => $request->no_telp ?? $user->no_telp,
                'alamat'  => $request->alamat_jemput ?? $user->alamat,
            ]);

            // 1. SIMPAN KE tr_reservasi
            $reservasi = Reservasi::create([
                'id_user'           => $user->id_user,
                'tanggal_reservasi' => now()->toDateString(),
                'jumlah_sepatu'     => $request->jumlah_sepatu,
                'metode_layanan'    => $request->metode_layanan, 
                'status'            => 'Menunggu Konfirmasi', 
                'total_harga'       => $total_harga,
                'alamat_jemput'     => $request->alamat_jemput,
            ]);

            // 2. SIMPAN KE tr_detail_reservasi
            DetailReservasi::create([
                'id_reservasi' => $reservasi->id_reservasi,
                'id_layanan'   => $request->id_layanan,
                'harga'        => $layanan->harga,
            ]);

            // 3. LOGIKA UPLOAD & SIMPAN tr_pembayaran
            $nama_file = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $nama_file = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
            }

            Pembayaran::create([
                'id_reservasi'      => $reservasi->id_reservasi,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_bayar'     => $nama_file ? now() : null,
                'status_pembayaran' => $nama_file ? 'Menunggu Validasi' : 'Belum Bayar',
                'bukti_pembayaran'  => $nama_file,
            ]);

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Hore! Reservasi Berhasil. Silakan pantau statusnya.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($nama_file)) {
                Storage::disk('public')->delete($nama_file);
            }
            return back()->withErrors(['sistem' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Fungsi Konfirmasi Pengembalian (Update Status & Detail Antar Kurir)
     */
    public function pilihPengembalian(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'metode'             => 'required|in:Ambil di Toko,Diantar ke Alamat',
            'wa_pengantaran'     => 'nullable|string|max:20',
            'alamat_pengantaran' => 'nullable|string',
        ]);

        // 2. Cari Reservasi milik User
        $reservasi = Reservasi::where('id_reservasi', $id)
                    ->where('id_user', auth()->user()->id_user)
                    ->firstOrFail();

        DB::beginTransaction();

        try {
            if ($request->metode == 'Diantar ke Alamat') {
                // LOGIKA ANTAR KURIR
                $reservasi->update([
                    'metode_pengembalian' => $request->metode,
                    'wa_pengantaran'      => $request->wa_pengantaran,
                    'alamat_pengantaran'  => $request->alamat_pengantaran,
                    'status'              => 'Menunggu Kurir'
                ]);

                // 🚨 SKENARIO OTOMATIS: Update profil user jika saat pesanan awal dia belum masukin WA
                $user = auth()->user();
                $user->update([
                    'no_telp' => $request->wa_pengantaran ?? $user->no_telp,
                    'alamat'  => $request->alamat_pengantaran ?? $user->alamat
                ]);

                $msg = 'Detail pengantaran berhasil disimpan. Kurir akan segera meluncur!';
            } else {
                // LOGIKA AMBIL SENDIRI DI TOKO
                $reservasi->update([
                    'metode_pengembalian' => $request->metode,
                    'status'              => 'Siap Diambil'
                ]);
                $msg = 'Pilihan berhasil disimpan! Anda bisa mengambil sepatu di toko sekarang.';
            }

            DB::commit();
            return redirect()->back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}