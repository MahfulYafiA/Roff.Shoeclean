<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;

class LaporanController extends Controller
{
    public function index()
    {
        // Mengambil semua pesanan yang sudah 'Selesai' untuk dihitung omzetnya
        $laporan = Reservasi::with(['user', 'layanan'])
                            ->where('status', 'Selesai')
                            ->orderBy('tanggal_reservasi', 'desc')
                            ->get();

        // Menghitung total omzet dari semua cucian yang selesai
        $totalOmzet = $laporan->sum('total_harga');

        return view('admin.laporan', compact('laporan', 'totalOmzet'));
    }
}