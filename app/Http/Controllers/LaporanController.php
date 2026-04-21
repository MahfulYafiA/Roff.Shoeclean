<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;

class LaporanController extends Controller
{
    /**
     * Menampilkan Laporan dengan Filter Periode
     */
    public function index(Request $request)
    {
        // 1. Ambil input filter tanggal dari user
        $tgl_mulai = $request->get('tgl_mulai');
        $tgl_selesai = $request->get('tgl_selesai');

        // 2. Query Data dengan Eager Loading (Termasuk relasi pembayaran untuk revisi detail)
        $query = Reservasi::with(['user', 'layanan', 'pembayaran'])
                    ->where('status', 'Selesai') // Laporan biasanya hanya untuk yang sudah selesai
                    ->orderBy('tanggal_reservasi', 'desc');

        // 3. Logika Filter Periode
        if ($tgl_mulai && $tgl_selesai) {
            // Jika user memilih rentang tanggal
            $query->whereBetween('tanggal_reservasi', [$tgl_mulai, $tgl_selesai]);
        } else {
            // Default: Tampilkan data bulan berjalan jika filter kosong
            $query->whereMonth('tanggal_reservasi', date('m'))
                  ->whereYear('tanggal_reservasi', date('Y'));
        }

        $laporan = $query->get();

        // 4. Menghitung total omzet khusus dari data yang sudah di-filter
        $totalOmzet = $laporan->sum('total_harga');

        // 5. Kirim data ke view
        return view('admin.laporan', compact('laporan', 'totalOmzet', 'tgl_mulai', 'tgl_selesai'));
    }
}