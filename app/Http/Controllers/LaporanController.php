<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;

class LaporanController extends Controller
{
    /**
     * Menampilkan Laporan untuk ADMIN
     */
    public function index(Request $request)
    {
        // 1. Tangkap input filter tanggal (default: hari ini)
        $tgl_mulai = $request->get('tgl_mulai', now()->toDateString());
        $tgl_selesai = $request->get('tgl_selesai', now()->toDateString());

        // 2. Query Data dengan tambahan klausa filter rentang tanggal
        $laporan = Reservasi::with(['user', 'detail.layanan'])
                    ->where('status', 'selesai') 
                    ->whereBetween('tanggal_reservasi', [$tgl_mulai, $tgl_selesai])
                    ->orderBy('updated_at', 'desc')
                    ->get();

        // 3. Hitung total omzet
        $totalOmzet = $laporan->sum('total_harga');

        // 4. Kirim data ke view admin.laporan
        return view('admin.laporan', compact('laporan', 'totalOmzet', 'tgl_mulai', 'tgl_selesai'));
    }
}