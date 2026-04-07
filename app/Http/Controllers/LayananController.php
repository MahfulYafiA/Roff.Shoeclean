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
        $layanans = Layanan::orderBy('created_at', 'desc')->get();
        return view('admin.layanan', compact('layanans'));
    }

    /**
     * Menyimpan layanan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
        ]);

        try {
            Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'harga'        => $request->harga,
                'deskripsi'    => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Layanan baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah layanan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data layanan yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
        ]);

        try {
            $layanan = Layanan::findOrFail($id);
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'harga'        => $request->harga,
                'deskripsi'    => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Data layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update layanan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus layanan dari database
     */
    public function destroy($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            $layanan->delete();

            return redirect()->back()->with('success', 'Layanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus layanan: ' . $e->getMessage());
        }
    }
}