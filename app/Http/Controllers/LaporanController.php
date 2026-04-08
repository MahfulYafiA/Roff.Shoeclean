<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input filter dari user (jika ada)
        $bulan = $request->get('bulan', date('m')); // Default: Bulan sekarang
        $tahun = $request->get('tahun', date('Y')); // Default: Tahun sekarang

        // 2. Query Data dengan Eager Loading
        $laporan = Reservasi::with(['user', 'layanan'])
                    ->where('status', 'Selesai')
                    // Filter berdasarkan bulan dan tahun pada kolom tanggal_reservasi
                    ->whereMonth('tanggal_reservasi', $bulan)
                    ->whereYear('tanggal_reservasi', $tahun)
                    ->orderBy('tanggal_reservasi', 'desc')
                    ->get();

        // 3. Menghitung total omzet khusus dari data yang sudah di-filter
        $totalOmzet = $laporan->sum('total_harga');

        // 4. Kirim data ke view
        return view('admin.laporan', compact('laporan', 'totalOmzet', 'bulan', 'tahun'));
    }
}