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
     * Menampilkan Form Pembayaran (Pemicu Snap Midtrans)
     */
    public function pembayaran($id)
    {
        $reservasi = Reservasi::with(['user', 'detail.layanan', 'pembayaran'])
                    ->where('id_reservasi', $id)
                    ->where('id_user', auth()->user()->id_user)
                    ->firstOrFail();

        if ($reservasi->status_bayar == 'Lunas') {
            return redirect()->route('reservasi.riwayat')->with('success', 'Pesanan ini sudah lunas.');
        }

        try {
            $snapToken = $this->generateMidtransToken($reservasi);
            return view('pelanggan.reservasi.pembayaran', compact('reservasi', 'snapToken'));
        } catch (\Exception $e) {
            return redirect()->route('reservasi.riwayat')->with('error', 'Gagal memuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan data reservasi ke Database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Metode pembayaran otomatis di-set di form)
        $request->validate([
            'layanan'           => 'required|array',
            'metode_masuk'      => 'required|in:Antar Sendiri,Jemput Kurir',
            'metode_keluar'     => 'required|in:Ambil Sendiri,Antar Kurir',
            'metode_pembayaran' => 'required',
            'alamat_jemput'     => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();
            $total_harga = 0;
            $items_terpilih = [];

            // 2. Kalkulasi Total dan Siapkan Item
            foreach ($request->layanan as $id_layanan => $data) {
                $qty = (int) $data['jumlah'];
                if ($qty > 0) {
                    $layanan = Layanan::findOrFail($id_layanan);
                    $sub_total = $layanan->harga * $qty;
                    $total_harga += $sub_total;

                    $items_terpilih[] = [
                        'id_layanan' => $id_layanan,
                        'harga'      => $layanan->harga,
                        'jumlah'     => $qty,
                        'sub_total'  => $sub_total,
                        'nama'       => $layanan->nama_layanan
                    ];
                }
            }

            if (empty($items_terpilih)) {
                return back()->withErrors(['Mohon tentukan jumlah pasang sepatu minimal 1 pada layanan yang dipilih.'])->withInput();
            }

            // Update alamat jika user mengisi alamat baru di form
            if ($request->alamat_jemput) {
                User::where('id_user', $user->id_user)->update(['alamat' => $request->alamat_jemput]);
            }

            // 3. SIMPAN KE tr_reservasi (Header)
            $reservasi = Reservasi::create([
                'id_user'           => $user->id_user,
                'tanggal_reservasi' => now()->toDateString(),
                'metode_layanan'    => ($request->metode_masuk == 'Jemput Kurir') ? 'Pick-up' : 'Drop-off', 
                'metode_masuk'      => $request->metode_masuk,
                'metode_keluar'     => $request->metode_keluar,
                'status'            => 'Menunggu Konfirmasi',
                'status_bayar'      => 'Belum Lunas', 
                'total_harga'       => $total_harga,
                'alamat_jemput'     => $request->alamat_jemput,
            ]);

            // 4. SIMPAN KE tr_detail_reservasi (Isi daftar cucian)
            foreach ($items_terpilih as $item) {
                DetailReservasi::create([
                    'id_reservasi' => $reservasi->id_reservasi,
                    'id_layanan'   => $item['id_layanan'],
                    'harga'        => $item['harga'],
                    'jumlah'       => $item['jumlah'],
                    'sub_total'    => $item['sub_total'],
                ]);
            }

            // 5. SIMPAN KE tr_pembayaran (Rekod awal pembayaran)
            Pembayaran::create([
                'id_reservasi' => $reservasi->id_reservasi,
                'metode_bayar' => 'Payment Gateway',
            ]);

            // SEMUA BERHASIL? KITA COMMIT!
            DB::commit();

            // 6. LANJUT KE PROSES MIDTRANS
            $reservasiLengkap = Reservasi::with(['user', 'detail.layanan'])->find($reservasi->id_reservasi);
            $snapToken = $this->generateMidtransToken($reservasiLengkap);

            // ✅ Tampilkan view pembayaran yang memicu Snap Popup DAN bawa pesan sukses
            return view('pelanggan.reservasi.pembayaran', [
                'reservasi'   => $reservasiLengkap,
                'snapToken'   => $snapToken,
                'success_msg' => 'Reservasi berhasil! Mengarahkan ke pembayaran...'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("ERROR RESERVASI: " . $e->getMessage());
            return back()->withErrors(['Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Fungsi Pembuat Snap Token Midtrans
     */
    private function generateMidtransToken($reservasi)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        $item_details = [];
        foreach ($reservasi->detail as $item) {
            $item_details[] = [
                'id'       => (string) $item->id_layanan,
                'price'    => (int) $item->harga,
                'quantity' => (int) $item->jumlah,
                'name'     => substr($item->layanan->nama_layanan ?? 'Jasa Cuci', 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $reservasi->id_reservasi . '-' . time(),
                'gross_amount' => (int) $reservasi->total_harga,
            ],
            'customer_details' => [
                'first_name' => $reservasi->user->nama,
                'email'      => $reservasi->user->email,
                'phone'      => $reservasi->user->no_telp ?? '',
            ],
            'item_details'     => $item_details,
            'enabled_payments' => ['bca_va', 'dana', 'qris', 'other_qris', 'gopay', 'shopeepay']
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Webhook Midtrans Callback (Otomatis Update Status)
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
                    if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                        $reservasi->update(['status' => 'Diproses', 'status_bayar' => 'Lunas']);
                        $pembayaran->update([
                            'tanggal' => now(),
                            'jumlah' => $request->gross_amount,
                            'metode_bayar' => 'Payment Gateway'
                        ]);
                    } else if (in_array($request->transaction_status, ['deny', 'expire', 'cancel'])) {
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