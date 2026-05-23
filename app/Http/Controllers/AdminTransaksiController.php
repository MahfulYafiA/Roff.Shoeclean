<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Models\DetailReservasi;
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
        // Hanya ambil layanan yang statusnya 'Aktif'
        $layanans = Layanan::where('status', 'Aktif')->get();
        return view('admin.transaksi.offline', compact('layanans'));
    }

    /**
     * Memproses penyimpanan transaksi offline multi-layanan
     */
    public function storeOffline(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'nama_pelanggan' => 'required|string|max:40',
            'no_whatsapp'    => 'nullable|string|max:20', 
            'layanan'        => 'required|array', 
            'metode_bayar'   => 'required|string',
            'status_lunas'   => 'required|string|in:Lunas,Belum Lunas', 
            'metode_keluar'  => 'required|string', 
            'alamat_lengkap' => 'nullable|string|max:200',
        ]);

        // 2. Filter Pesanan Kosong (Hanya simpan layanan dengan QTY > 0)
        $pesanan_aktif = array_filter($request->layanan, function($jumlah) {
            return $jumlah > 0;
        });

        if(empty($pesanan_aktif)) {
            return redirect()->back()->withErrors(['Harap pilih minimal 1 layanan cucian!'])->withInput();
        }

        DB::beginTransaction();

        try {
            // 3. Buat Akun Dummy untuk Pelanggan Walk-in
            $user_offline = User::firstOrCreate(
                ['email' => 'offline_' . uniqid() . '@roff.com'], 
                [
                    'nama'     => $request->nama_pelanggan . ' (Walk-in)',
                    'password' => Hash::make('rahasia_offline'),
                    'role'     => 'pelanggan',
                    'status'   => 'Aktif',
                    'no_telp'  => $request->no_whatsapp ?? '-',
                ]
            );

            // 4. Kalkulasi Grand Total secara Dinamis dari Server
            $grand_total = 0;
            $detail_items = []; 
            
            foreach($pesanan_aktif as $id_layanan => $jumlah) {
                $layanan = Layanan::findOrFail($id_layanan);
                $sub_total = $layanan->harga * $jumlah;
                $grand_total += $sub_total;

                $detail_items[] = [
                    'id_layanan' => $id_layanan,
                    'harga'      => $layanan->harga,
                    'jumlah'     => $jumlah,
                    'sub_total'  => $sub_total
                ];
            }

            // 5. Simpan Header Reservasi
            $reservasi = Reservasi::create([
                'id_user'           => $user_offline->id_user,
                'tanggal_reservasi' => now()->toDateString(),
                'metode_layanan'    => 'Drop-off', 
                'metode_masuk'      => 'Antar Sendiri', 
                'metode_keluar'     => $request->metode_keluar, 
                'status'            => 'diproses',      
                'status_bayar'      => $request->status_lunas,
                'total_harga'       => $grand_total,
                'alamat_lengkap'    => $request->alamat_lengkap ?? 'Pelanggan Walk-in (Tanpa Alamat)', 
                'metode_bayar'      => $request->metode_bayar,
                'tanggal_bayar'     => ($request->status_lunas == 'Lunas') ? now() : null,
            ]);

            // 6. Simpan Detail Reservasi (Multi-Layanan)
            foreach($detail_items as $item) {
                DetailReservasi::create([
                    'id_reservasi' => $reservasi->id_reservasi, 
                    'id_layanan'   => $item['id_layanan'],
                    'harga'        => $item['harga'],
                    'jumlah'       => $item['jumlah'],
                    'sub_total'    => $item['sub_total'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.antrean')->with('success', 'Transaksi Kasir Offline berhasil! (Atas Nama: ' . $request->nama_pelanggan . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Gagal Menyimpan Transaksi: ' . $e->getMessage()])->withInput();
        }
    }
}