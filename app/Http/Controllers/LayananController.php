<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 

class LayananController extends Controller
{
    /**
     * Menampilkan daftar layanan untuk Admin & Superadmin
     */
    public function index()
    {
        $layanans = Layanan::orderBy('id_layanan', 'desc')->get();

        // Mengarahkan ke view masing-masing role (karena Sidebar/Tema Warna berbeda)
        if (auth()->user()->role === 'superadmin') {
            return view('superadmin.layanan', compact('layanans'));
        }

        return view('admin.layanan', compact('layanans'));
    }

    /**
     * Update Foto Banner Hero (Universal: Bisa diakses Admin & Superadmin)
     */
    public function updateHero(Request $request)
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'hero_image.required' => 'Pilih foto terlebih dahulu.',
            'hero_image.image'    => 'File harus berupa gambar.',
            'hero_image.max'      => 'Ukuran foto maksimal 2MB.'
        ]);

        try {
            if ($request->hasFile('hero_image')) {
                // 1. Ambil data lama untuk proses penghapusan file fisik
                $oldSetting = DB::table('ms_pengaturan')->where('key', 'hero_image')->first();
                
                if ($oldSetting && $oldSetting->value) {
                    // Hapus foto lama dari storage agar hemat ruang disk
                    Storage::disk('public')->delete($oldSetting->value);
                }

                // 2. Simpan foto baru ke folder 'banners' di public storage
                $path = $request->file('hero_image')->store('banners', 'public');

                // 3. Update database menggunakan updateOrInsert untuk keamanan data
                DB::table('ms_pengaturan')->updateOrInsert(
                    ['key' => 'hero_image'],
                    [
                        'value' => $path,
                        'updated_at' => now()
                    ]
                );

                return redirect()->back()->with('success', 'Banner Website berhasil diperbarui!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update banner: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan layanan baru ke database (Admin & Superadmin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string|max:255',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama_layanan.required' => 'Nama jasa tidak boleh kosong.',
            'harga.numeric'         => 'Harga harus berupa angka.',
            'gambar.image'          => 'File harus berupa gambar.',
            'gambar.max'            => 'Ukuran gambar maksimal 2MB.'
        ]);

        try {
            $pathGambar = null;
            if ($request->hasFile('gambar')) {
                $pathGambar = $request->file('gambar')->store('layanans', 'public');
            }

            Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'harga'        => $request->harga,
                'deskripsi'    => $request->deskripsi,
                'gambar'       => $pathGambar,
            ]);

            return redirect()->back()->with('success', 'Layanan jasa baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah layanan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data layanan + Ganti Foto (Admin & Superadmin)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string|max:255',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $layanan = Layanan::findOrFail($id);
            $pathGambar = $layanan->gambar;

            if ($request->hasFile('gambar')) {
                // Hapus file lama jika Admin/Superadmin upload foto baru
                if ($layanan->gambar) {
                    Storage::disk('public')->delete($layanan->gambar);
                }
                $pathGambar = $request->file('gambar')->store('layanans', 'public');
            }

            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'harga'        => $request->harga,
                'deskripsi'    => $request->deskripsi,
                'gambar'       => $pathGambar,
            ]);

            return redirect()->back()->with('success', 'Detail layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update layanan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus layanan + Hapus file foto terkait
     */
    public function destroy($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            
            // Pastikan file gambar di folder public ikut terhapus
            if ($layanan->gambar) {
                Storage::disk('public')->delete($layanan->gambar);
            }

            $layanan->delete();

            return redirect()->back()->with('success', 'Layanan berhasil dihapus dari daftar.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus layanan.');
        }
    }
}