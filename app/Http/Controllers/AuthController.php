<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    /**
     * FR01: Proses Registrasi Akun
     */
    public function register(Request $request)
    {
        // 1. Validasi disesuaikan dengan limit karakter di Migrasi ms_user
        $request->validate([
            'nama' => 'required|string|max:40', // Sesuai Workbench (40)
            'no_telp' => 'required|string|max:15', // Sesuai Workbench (15)
            'email' => 'required|string|email|max:50|unique:ms_user', // Sesuai Workbench (50)
            'password' => 'required|string|min:8|confirmed', 
        ], 
        [
            'nama.max' => 'Nama terlalu panjang, maksimal 40 karakter.',
            'no_telp.max' => 'Nomor telepon maksimal 15 angka.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // 2. Simpan data ke database (ms_user)
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password), 
            'id_role' => 3, // Default: Pelanggan
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang di ROFF SHOECLEAN!');
    }

    /**
     * FR02: Proses Login dengan Multi-Role Redirect
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Logika redirect dipisah ke fungsi agar rapi (Re-useable)
            return $this->redirectUser(auth()->user());
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    /**
     * Fungsi Helper untuk Redirect berdasarkan Role
     */
    private function redirectUser($user)
    {
        if ($user->id_role == 1) {
            return redirect()->route('superadmin.dashboard'); 
        } elseif ($user->id_role == 2) {
            return redirect()->route('admin.dashboard'); 
        } else {
            return redirect()->route('dashboard'); 
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Sampai jumpa kembali!');
    }

    // ==========================================
    // FUNGSI LOGIN GOOGLE (SOCIALITE)
    // ==========================================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update Google ID jika belum ada
                $user->update(['google_id' => $googleUser->getId()]);
                Auth::login($user);
            } else {
                // Buat user baru jika belum terdaftar
                $user = User::create([
                    'nama' => substr($googleUser->getName(), 0, 40), // Potong jika nama Google > 40 char
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'id_role' => 3, 
                    'password' => Hash::make(now()), // Password acak aman
                ]);
                Auth::login($user);
            }

            return $this->redirectUser($user);

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login Google.');
        }
    }
}