<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * 1. Menampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();
        return view('profil.index', compact('user'));
    }

    /**
     * 2. Update Informasi Pribadi (Form Tengah)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi disesuaikan dengan atribut "name" di HTML (no_hp & alamat)
        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20', 
            'email' => 'required|string|email|max:100|unique:ms_user,email,' . $user->id_user . ',id_user',
            'alamat'=> 'nullable|string',
        ]);

        // Menyimpan data ke kolom database (no_telp & alamat)
        $user->update([
            'nama'    => $request->nama,
            'email'   => $request->email,
            'no_telp' => $request->no_hp, // Mengambil dari input no_hp ke kolom no_telp
            'alamat'  => $request->alamat,
        ]);

        return back()->with('success', 'Informasi pribadi berhasil diperbarui!');
    }

    /**
     * 3. Update Foto Profil (Form Kiri)
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Simpan foto baru
        $path = $request->file('foto_profil')->store('profil', 'public');

        $user->foto_profil = $path;
        $user->save(); 

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * 4. Update Password (Form Kanan)
     */
    public function updatePassword(Request $request)
    {
        // Sesuaikan validasi dengan name="new_password" di HTML
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'new_password.min'       => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        
        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password akun berhasil diganti!');
    }

    /**
     * 5. Hapus Foto Profil
     */
    public function hapusFoto()
    {
        $user = Auth::user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->foto_profil = null;
        $user->save();

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }
}