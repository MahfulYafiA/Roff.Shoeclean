<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Models\DetailReservasi;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

// Impor library Midtrans
use Midtrans\Config;
use Midtrans\Snap;

class ReservasiController extends Controller
{
    /**
     * Menampilkan Form Reservasi
     */
    public function create()
    {
        $layanans = Layanan::where('is_active', true)->get(); 
        $user = auth()->user();
        
        return view('pelanggan.reservasi.create', compact('layanans', 'user'));
    }

    /**
     * Menampilkan Form Pembayaran Midtrans (Bayar Nanti dari Riwayat)
     */
    public function pembayaran($id)
    {
        $reservasi = Reservasi::with(['user', 'layanan', 'pembayaran'])
                    ->where('id_reservasi', $id)
                    ->where('id_user', auth()->user()->id_user)
                    ->firstOrFail();

        if ($reservasi->status_bayar == 'Lunas') {
            return redirect()->route('reservasi.riwayat')->with('success', 'Pesanan ini sudah lunas atau sedang diproses.');
        }

        $snapToken = $this->generateMidtransToken($reservasi);

        return view('pelanggan.reservasi.pembayaran', compact('reservasi', 'snapToken'));
    }

    /**
     * Menyimpan data reservasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan'        => 'required|exists:ms_layanan,id_layanan',
            'jumlah_sepatu'     => 'required|integer|min:1',
            'metode_masuk'      => 'required|in:Antar Sendiri,Jemput Kurir', // 🚨 VALIDASI BARU
            'metode_keluar'     => 'required|in:Ambil Sendiri,Antar Kurir', // 🚨 VALIDASI BARU
            'metode_pembayaran' => 'required',
            'alamat_jemput'     => 'nullable|string|max:200', 
            'no_telp'           => 'nullable|string|max:15', 
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();
            $layanan = Layanan::findOrFail($request->id_layanan);
            
            $harga_satuan = $layanan->harga;
            $jumlah_sepatu = $request->jumlah_sepatu;
            $sub_total = $harga_satuan * $jumlah_sepatu;
            $total_harga = $sub_total;

            if ($request->alamat_jemput) {
                User::where('id_user', $user->id_user)->update([
                    'alamat' => $request->alamat_jemput
                ]);
            }

            // 1. SIMPAN KE tr_reservasi (DENGAN 2 KOLOM BARU)
            $reservasi = Reservasi::create([
                'id_user'             => $user->id_user,
                'tanggal_reservasi'   => now()->toDateString(),
                'metode_layanan'      => ($request->metode_masuk == 'Jemput Kurir') ? 'Pick-up' : 'Drop-off', 
                'metode_masuk'        => $request->metode_masuk, // ✅ DISIMPAN DISINI
                'metode_keluar'       => $request->metode_keluar, // ✅ DISIMPAN DISINI
                'status'              => 'Menunggu Konfirmasi',
                'status_bayar'        => 'Belum Lunas', 
                'total_harga'         => $total_harga,
                'alamat_jemput'       => $request->alamat_jemput,
            ]);

            $id_res_baru = $reservasi->id_reservasi; 

            // 2. SIMPAN KE tr_detail_reservasi
            DetailReservasi::create([
                'id_reservasi' => $id_res_baru,
                'id_layanan'   => $request->id_layanan,
                'harga'        => $harga_satuan,
                'jumlah'       => $jumlah_sepatu, 
                'sub_total'    => $sub_total,     
            ]);

            // 3. SIMPAN KE tr_pembayaran
            $metode_bayar_db = ($request->metode_pembayaran == 'Payment Gateway') ? 'Payment Gateway' : 'Tunai';
            
            Pembayaran::create([
                'id_reservasi'  => $id_res_baru,
                'tanggal'       => null, 
                'jumlah'        => null, 
                'metode_bayar'  => $metode_bayar_db,
            ]);

            DB::commit();

            if ($request->metode_pembayaran == 'Payment Gateway') {
                $reservasiLengkap = Reservasi::with(['user', 'detail.layanan', 'pembayaran'])->find($id_res_baru);
                $snapToken = $this->generateMidtransToken($reservasiLengkap);

                return view('pelanggan.reservasi.pembayaran', [
                    'reservasi' => $reservasiLengkap,
                    'snapToken' => $snapToken
                ]);
            }

            return redirect()->route('reservasi.riwayat')->with('success', 'Reservasi berhasil! Silakan lakukan pembayaran di kasir kami.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Gagal Menyimpan Data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Fungsi Helper untuk membuat Snap Token Midtrans
     */
    private function generateMidtransToken($reservasi)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        $detail = $reservasi->detail->first();
        $namaLayanan = $detail->layanan->nama_layanan ?? 'Jasa Cuci Sepatu';
        $jumlahBeli = $detail->jumlah ?? 1;
        $formattedItemName = $namaLayanan . " (" . $jumlahBeli . " Pasang)";

        $params = array(
            'transaction_details' => array(
                'order_id' => $reservasi->id_reservasi . '-' . time(),
                'gross_amount' => $reservasi->total_harga,
            ),
            'customer_details' => array(
                'first_name' => $reservasi->user->nama ?? 'Pelanggan',
                'email'      => $reservasi->user->email ?? 'pelanggan@email.com',
                'phone'      => $reservasi->user->no_telp ?? '-',
            ),
            'item_details' => array(
                array(
                    'id'       => $detail->id_layanan ?? '1',
                    'price'    => $detail->harga ?? $reservasi->total_harga,
                    'quantity' => $jumlahBeli,
                    'name'     => substr($formattedItemName, 0, 50) 
                )
            ),
            'enabled_payments' => array('bca_va', 'dana', 'qris', 'other_qris', 'gopay')
        );

        return Snap::getSnapToken($params);
    }

    /**
     * Fungsi Update Pengembalian
     */
    public function pilihPengembalian(Request $request, $id)
    {
        $request->validate([
            'metode'             => 'required|in:Ambil di Toko,Diantar ke Alamat',
            'wa_pengantaran'     => 'required_if:metode,Diantar ke Alamat|max:15',
            'alamat_pengantaran' => 'required_if:metode,Diantar ke Alamat|max:200',
        ]);

        $reservasi = Reservasi::where('id_reservasi', $id)
                    ->where('id_user', auth()->user()->id_user)
                    ->firstOrFail();

        DB::beginTransaction();

        try {
            if ($request->metode == 'Diantar ke Alamat') {
                $reservasi->update([
                    'status'              => 'Menunggu Kurir',
                    'wa_pengantaran'      => $request->wa_pengantaran,
                    'alamat_pengantaran'  => $request->alamat_pengantaran,
                    'metode_keluar'       => 'Antar Kurir', // Update konsistensi data
                ]);
                $msg = 'Siap! Kurir akan segera mengantar sepatu Anda.';
            } else {
                $reservasi->update([
                    'status'              => 'Siap Diambil',
                    'metode_keluar'       => 'Ambil Sendiri', // Update konsistensi data
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

    /**
     * FUNGSI WEBHOOK / CALLBACK MIDTRANS
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $id_asli = explode('-', $request->order_id)[0];
            $reservasi = Reservasi::find($id_asli);
            $pembayaran = Pembayaran::where('id_reservasi', $id_asli)->first();

            if ($reservasi && $pembayaran) {
                DB::beginTransaction();
                try {
                    if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                        $reservasi->update([
                            'status' => 'Diproses',
                            'status_bayar' => 'Lunas' 
                        ]);
                        $pembayaran->update([
                            'tanggal' => now(),
                            'jumlah' => $request->gross_amount,
                            'metode_bayar' => 'Payment Gateway'
                        ]);
                    } else if ($request->transaction_status == 'deny' || $request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                        $reservasi->update(['status' => 'Dibatalkan']);
                    }
                    
                    DB::commit();
                    return response()->json(['message' => 'Success'], 200);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['message' => 'Error'], 500);
                }
            }
        }
        return response()->json(['message' => 'Invalid Signature'], 403);
    }
}