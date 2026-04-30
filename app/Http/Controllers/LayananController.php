<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::orderBy('id_layanan', 'desc')->get();
        $userRole = auth()->user()->role;

        if ($userRole === 'superadmin') {
            return view('superadmin.layanan', compact('layanans'));
        } elseif ($userRole === 'admin') {
            return view('admin.layanan', compact('layanans'));
        } else {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function updateHero(Request $request)
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('hero_image')) {
                // KITA PAKSA HAPUS DATA LAMA
                $oldSetting = DB::table('ms_pengaturan')->where('key', 'hero_image')->first();
                if ($oldSetting && $oldSetting->value) {
                    Storage::disk('public')->delete($oldSetting->value);
                }

                // SIMPAN DENGAN NAMA TETAP (TIDAK ACAK)
                $path = 'banners/banner_utama.png';
                // Gunakan fitur Storage::put untuk menimpa file dengan aman
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

                // SIMPAN DENGAN NAMA TETAP
                $path = 'tentang/tentang_toko.png';
                Storage::disk('public')->put($path, file_get_contents($request->file('tentang_image')));

                DB::table('ms_pengaturan')->updateOrInsert(
                    ['key' => 'tentang_image'],
                    ['value' => $path, 'updated_at' => now()]
                );

                return redirect()->back()->with('success', 'Gambar "Tentang Kami" berhasil diperbarui!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update gambar: ' . $e->getMessage());
        }
    }

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
            return redirect()->back()->with('error', 'Gagal menambah layanan: ' . $e->getMessage());
        }
    }

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
            return redirect()->back()->with('error', 'Gagal update layanan: ' . $e->getMessage());
        }
    }

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

    public function toggleStatus($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            $layanan->status = ($layanan->status === 'Aktif') ? 'Nonaktif' : 'Aktif';
            $layanan->save();

            $msg = $layanan->status === 'Aktif' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Layanan {$layanan->nama_layanan} berhasil {$msg}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}