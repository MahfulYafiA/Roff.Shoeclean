<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'   => 'required|string|max:40', // Sesuai VARCHAR(40)
            'no_hp'  => 'nullable|string|max:15', // Sesuai VARCHAR(15)
            'email'  => 'required|string|email|max:50|unique:ms_user,email,' . $user->id_user . ',id_user',
            'alamat' => 'nullable|string|max:200', // Sesuai VARCHAR(200) - Update Final
        ], [
            'nama.max' => 'Nama terlalu panjang, maksimal 40 karakter.',
            'no_hp.max' => 'Nomor WA maksimal 15 angka.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'alamat.max' => 'Alamat terlalu panjang, maksimal 200 karakter.',
        ]);

        $user->update([
            'nama'    => $request->nama,
            'email'   => $request->email,
            'no_telp' => $request->no_hp, 
            'alamat'  => $request->alamat,
        ]);

        return back()->with('success', 'Informasi profil berhasil diperbarui!');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ], [
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max'   => 'Ukuran foto maksimal 2MB.',
        ]);

        $user = Auth::user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $path = $request->file('foto_profil')->store('profil', 'public');

        $user->foto_profil = $path;
        $user->save(); 

        return back()->with('success', 'Foto profil baru berhasil dipasang!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'new_password.min'       => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Kata sandi akun berhasil diubah.');
    }

    public function hapusFoto()
    {
        $user = Auth::user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->foto_profil = null;
        $user->save();

        return back()->with('success', 'Foto profil telah dihapus.');
    }
}