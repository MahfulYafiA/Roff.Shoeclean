<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;

class LayananController extends Controller
{
    /**
     * 1. Menampilkan Daftar Layanan (Universal Admin & Superadmin)
     */
    public function index()
    {
        $layanans = Layanan::orderBy('id_layanan', 'desc')->get();
        $userRole = Auth::user()->role;

        // Mengarahkan ke view yang sesuai dengan role
        if ($userRole === 'superadmin') {
            return view('superadmin.layanan', compact('layanans'));
        } elseif ($userRole === 'admin') {
            return view('admin.layanan', compact('layanans'));
        } 
        
        return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
    }

    /**
     * 2. Update Banner Utama Website
     */
    public function updateHero(Request $request)
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('hero_image')) {
                // Cari data lama untuk dihapus filenya
                $oldSetting = DB::table('ms_pengaturan')->where('key', 'hero_image')->first();
                if ($oldSetting && $oldSetting->value) {
                    Storage::disk('public')->delete($oldSetting->value);
                }

                // Simpan dengan nama tetap agar mudah dipanggil
                $path = 'banners/banner_utama.png';
                Storage::disk('public')->put($path, file_get_contents($request->file('hero_image')));

                DB::table('ms_pengaturan')->updateOrInsert(
                    ['key' => 'hero_image'],
                    ['value' => $path, 'updated_at' => now()]
                );

                return redirect()->back()->with('success', 'Banner Website berhasil diperbarui!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update banner: ' . $e->getMessage());
        }
    }

    /**
     * 3. Update Gambar "Tentang Kami"
     */
    public function updateTentang(Request $request)
    {
        $request->validate([
            'tentang_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('tentang_image')) {
                $oldSetting = DB::table('ms_pengaturan')->where('key', 'tentang_image')->first();
                if ($oldSetting && $oldSetting->value) {
                    Storage::disk('public')->delete($oldSetting->value);
                }

                $path = 'tentang/tentang_toko.png';
                Storage::disk('public')->put($path, file_get_contents($request->file('tentang_image')));

                DB::table('ms_pengaturan')->updateOrInsert(
                    ['key' => 'tentang_image'],
                    ['value' => $path, 'updated_at' => now()]
                );

                return redirect()->back()->with('success', 'Gambar Tentang Kami berhasil diperbarui!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update gambar: ' . $e->getMessage());
        }
    }

    /**
     * 4. Tambah Layanan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string|max:1000',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
                'status'       => 'Aktif',
            ]);

            return redirect()->back()->with('success', 'Layanan baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah layanan.');
        }
    }

    /**
     * 5. Update Data Layanan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string|max:1000',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $layanan = Layanan::findOrFail($id);
            $pathGambar = $layanan->gambar;

            if ($request->hasFile('gambar')) {
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
            return redirect()->back()->with('error', 'Gagal update layanan.');
        }
    }

    /**
     * 6. Hapus Layanan Permanen
     */
    public function destroy($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            if ($layanan->gambar) {
                Storage::disk('public')->delete($layanan->gambar);
            }
            $layanan->delete();
            return redirect()->back()->with('success', 'Layanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus layanan.');
        }
    }

    /**
     * 7. Saklar Status (Toggle)
     * Sinkron dengan route('layanan.toggle') di web.php
     */
    public function toggleStatus($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            
            // Toggle Aktif <-> Nonaktif
            $layanan->status = ($layanan->status === 'Aktif') ? 'Nonaktif' : 'Aktif';
            $layanan->save();

            $msg = $layanan->status === 'Aktif' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Layanan {$layanan->nama_layanan} berhasil {$msg}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status layanan.');
        }
    }
}