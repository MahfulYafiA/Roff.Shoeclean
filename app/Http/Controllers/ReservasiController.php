<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Models\DetailReservasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class ReservasiController extends Controller
{
    public function create()
    {
        $layanans = Layanan::where('status', 'Aktif')->get();
        $user = Auth::user();
        return view('pelanggan.reservasi.create', compact('layanans', 'user'));
    }

    public function pembayaran($id)
    {
        $reservasi = Reservasi::with(['user', 'detail.layanan'])
                    ->where('id_reservasi', $id)
                    ->where('id_user', Auth::user()->id_user)
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
     * SINKRONISASI ENUM PADA FUNGSI STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'layanan'           => 'required|array',
            'metode_masuk'      => 'required|in:Antar Sendiri,Jemput Kurir',
            // ✨ FIX: Sesuaikan dengan ENUM 'Diantar Kurir' (sebelumnya Antar Kurir)
            'metode_keluar'     => 'required|in:Ambil Sendiri,Diantar Kurir',
            'metode_pembayaran' => 'required',
            'alamat_lengkap'    => 'nullable|string|max:200', 
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $total_harga = 0;
            $items_terpilih = [];

            foreach ($request->layanan as $id_layanan => $data) {
                $qty = (int) $data['jumlah'];
                if ($qty > 0) {
                    $layanan = Layanan::findOrFail($id_layanan);
                    $total_harga += ($layanan->harga * $qty);
                    $items_terpilih[] = [
                        'id_layanan' => $id_layanan,
                        'harga'      => $layanan->harga,
                        'jumlah'     => $qty,
                        'sub_total'  => $layanan->harga * $qty
                    ];
                }
            }

            if (empty($items_terpilih)) {
                return back()->withErrors(['Mohon tentukan jumlah pasang sepatu minimal 1.'])->withInput();
            }

            if ($request->alamat_lengkap) {
                User::where('id_user', $user->id_user)->update(['alamat' => $request->alamat_lengkap]);
            }

            $reservasi = Reservasi::create([
                'id_user'           => $user->id_user,
                'tanggal_reservasi' => now()->toDateString(),
                'metode_layanan'    => ($request->metode_masuk == 'Jemput Kurir') ? 'Pick-up' : 'Drop-off', 
                'metode_masuk'      => $request->metode_masuk,
                'metode_keluar'     => $request->metode_keluar,
                'status'            => 'diajukan', 
                'status_bayar'      => 'Belum Lunas', 
                'total_harga'       => $total_harga,
                'alamat_lengkap'    => $request->alamat_lengkap,
                // ✨ FIX: Set NULL karena 'Payment Gateway' sudah dihapus dari ENUM
                'metode_bayar'      => null, 
                'tanggal_bayar'     => null, 
            ]);

            foreach ($items_terpilih as $item) {
                DetailReservasi::create([
                    'id_reservasi' => $reservasi->id_reservasi,
                    'id_layanan'   => $item['id_layanan'],
                    'harga'        => $item['harga'],
                    'jumlah'       => $item['jumlah'],
                    'sub_total'    => $item['sub_total'],
                ]);
            }

            DB::commit();

            $reservasiLengkap = Reservasi::with(['user', 'detail.layanan'])->find($reservasi->id_reservasi);
            $snapToken = $this->generateMidtransToken($reservasiLengkap);

            return view('pelanggan.reservasi.pembayaran', [
                'reservasi'   => $reservasiLengkap,
                'snapToken'   => $snapToken,
                'success_msg' => 'Reservasi berhasil! Mengarahkan ke pembayaran...'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

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
            // ✨ FIX: Batasi hanya BCA dan QRIS agar sesuai ENUM database
            'enabled_payments' => ['bca_va', 'qris']
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * ✨ FIX: LOGIKA CALLBACK YANG SANGAT KETAT SESUAI ENUM ('Transfer BCA', 'QRIS')
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $id_asli = explode('-', $request->order_id)[0];
            $reservasi = Reservasi::find($id_asli);

            if ($reservasi) {
                DB::beginTransaction();
                try {
                    if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                        
                        $paymentType = $request->payment_type;
                        $metodeFinal = "";

                        // Pemaksaan string agar sama persis dengan ENUM di database
                        if ($paymentType == 'bank_transfer' && $request->va_numbers[0]['bank'] == 'bca') {
                            $metodeFinal = 'Transfer BCA';
                        } elseif ($paymentType == 'qris') {
                            $metodeFinal = 'QRIS';
                        } else {
                            // Backup jika ada metode lain yang lolos, paksa ke salah satu agar tidak Error 500
                            $metodeFinal = 'Transfer BCA'; 
                        }

                        $reservasi->update([
                            'status'        => 'diproses', 
                            'status_bayar'  => 'Lunas',
                            'tanggal_bayar' => now(), 
                            'metode_bayar'  => $metodeFinal 
                        ]);
                        
                    } else if (in_array($request->transaction_status, ['deny', 'expire', 'cancel'])) {
                        $reservasi->update(['status' => 'batalkan']);
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

    public function cekStatusTerbaru()
    {
        $riwayat = Reservasi::where('id_user', Auth::user()->id_user)
                            ->select('id_reservasi', 'status')
                            ->get();
        return response()->json($riwayat);
    }
}