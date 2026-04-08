<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;

class LayananController extends Controller
{
    /**
     * Menampilkan daftar layanan untuk Admin
     */
    public function index()
    {
        // Menggunakan id_layanan karena timestamps (created_at) dinonaktifkan
        $layanans = Layanan::orderBy('id_layanan', 'desc')->get();
        return view('admin.layanan', compact('layanans'));
    }

    /**
     * Menyimpan layanan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100', // Disesuaikan agar pas di UI
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string|max:255', // Limit agar DB tetap ringan
        ], [
            'nama_layanan.required' => 'Nama jasa tidak boleh kosong.',
            'harga.numeric'         => 'Harga harus berupa angka.',
            'harga.min'             => 'Harga tidak boleh minus.'
        ]);

        try {
            Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'harga'        => $request->harga,
                'deskripsi'    => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Layanan "' . $request->nama_layanan . '" berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah layanan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data layanan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string|max:255',
        ]);

        try {
            $layanan = Layanan::findOrFail($id);
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'harga'        => $request->harga,
                'deskripsi'    => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update layanan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus layanan
     */
    public function destroy($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            
            // Cek apakah layanan ini sedang digunakan di tabel transaksi
            // Jika ada transaksi yang sedang berjalan, sebaiknya tidak dihapus (opsional)
            $layanan->delete();

            return redirect()->back()->with('success', 'Layanan berhasil dihapus dari daftar.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: Layanan ini mungkin masih terikat dengan data transaksi.');
        }
    }
}