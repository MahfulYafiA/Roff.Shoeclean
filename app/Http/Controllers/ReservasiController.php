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
     * Menampilkan Form Reservasi
     */
    public function create()
    {
        $layanans = Layanan::all(); 
        $user = auth()->user();
        
        return view('pelanggan.reservasi.create', compact('layanans', 'user'));
    }

    /**
     * Menampilkan Form Pembayaran (Jika User ingin bayar nanti)
     */
    public function pembayaran($id)
    {
        $reservasi = Reservasi::where('id_reservasi', $id)
                    ->where('id_user', auth()->user()->id_user)
                    ->firstOrFail();

        return view('pelanggan.reservasi.pembayaran', compact('reservasi'));
    }

    /**
     * Menyimpan data reservasi (Induk, Detail, dan Pembayaran)
     */
    public function store(Request $request)
    {
        // 1. Validasi Ketat
        $request->validate([
            'id_layanan'        => 'required|exists:ms_layanan,id_layanan',
            'jumlah_sepatu'     => 'required|integer|min:1',
            'metode_layanan'    => 'required|in:Drop-off,Pick-up',
            'metode_pembayaran' => 'required',
            'bukti_pembayaran'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alamat_jemput'     => 'nullable|string|max:255', 
            'no_telp'           => 'nullable|string|max:15', 
        ], [
            'no_telp.max' => 'Nomor WhatsApp maksimal 15 angka.',
            'bukti_pembayaran.max' => 'Ukuran foto bukti transfer maksimal 2MB.',
            'bukti_pembayaran.image' => 'File harus berupa gambar (JPG/PNG).'
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();
            $layanan = Layanan::findOrFail($request->id_layanan);
            $total_harga = $layanan->harga * $request->jumlah_sepatu;

            // Update alamat user jika dia menginputkan alamat penjemputan baru
            if ($request->alamat_jemput) {
                User::where('id_user', $user->id_user)->update([
                    'alamat' => $request->alamat_jemput
                ]);
            }

            // 1. SIMPAN KE tr_reservasi
            $reservasi = Reservasi::create([
                'id_user'             => $user->id_user,
                'tanggal_reservasi'   => now(),
                'jumlah_sepatu'       => $request->jumlah_sepatu,
                'metode_layanan'      => $request->metode_layanan, 
                'status'              => 'Menunggu Konfirmasi', 
                'total_harga'         => $total_harga,
                'alamat_jemput'       => $request->alamat_jemput,
                // ✅ Nilai default untuk kolom yang mewajibkan input
                'metode_pengembalian' => 'Belum Ditentukan', 
                'status_pengambilan'  => 'Belum Diambil', // <-- Penambahan Terbaru
            ]);

            // 2. SIMPAN KE tr_detail_reservasi (History Harga)
            $id_res_baru = $reservasi->id_reservasi ?? $reservasi->id; 

            DetailReservasi::create([
                'id_reservasi' => $id_res_baru,
                'id_layanan'   => $request->id_layanan,
                'harga'        => $layanan->harga,
            ]);

            // 3. LOGIKA PEMBAYARAN
            $nama_file = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $nama_file = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
            }

            Pembayaran::create([
                'id_reservasi'      => $id_res_baru,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_bayar'     => $nama_file ? now() : null,
                'status_pembayaran' => $nama_file ? 'Menunggu Validasi' : 'Belum Bayar',
                'bukti_pembayaran'  => $nama_file,
            ]);

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Reservasi berhasil dibuat! Tim kami akan segera mengecek pesanan Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($nama_file)) {
                Storage::disk('public')->delete($nama_file);
            }
            
            return back()->withErrors(['Sistem Database Menolak: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Fungsi Update Pengembalian (Update Status & WA Pengantaran)
     */
    public function pilihPengembalian(Request $request, $id)
    {
        $request->validate([
            'metode'             => 'required|in:Ambil di Toko,Diantar ke Alamat',
            'wa_pengantaran'     => 'nullable|string|max:15',
            'alamat_pengantaran' => 'nullable|string|max:255',
        ]);

        $reservasi = Reservasi::where('id_reservasi', $id)
                    ->where('id_user', auth()->user()->id_user)
                    ->firstOrFail();

        DB::beginTransaction();

        try {
            if ($request->metode == 'Diantar ke Alamat') {
                $reservasi->update([
                    'metode_pengembalian' => $request->metode,
                    'wa_pengantaran'      => $request->wa_pengantaran,
                    'alamat_pengantaran'  => $request->alamat_pengantaran,
                    'status'              => 'Menunggu Kurir'
                ]);
                $msg = 'Siap! Kurir akan segera mengantar sepatu Anda.';
            } else {
                $reservasi->update([
                    'metode_pengembalian' => $request->metode,
                    'status'              => 'Siap Diambil'
                ]);
                $msg = 'Silakan ambil sepatu Anda di toko kami.';
            }

            DB::commit();
            return redirect()->back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Gagal: ' . $e->getMessage()]);
        }
    }
}