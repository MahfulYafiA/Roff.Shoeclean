<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// TAMBAHAN WAJIB UNTUK GOOGLE LOGIN:
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Menampilkan halaman Login & Register
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    /**
     * FR01: Proses Registrasi Akun
     */
    public function register(Request $request)
    {
        // 1. Validasi data yang diinput user
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20', // 🚨 SEKARANG SUDAH DIVALIDASI
            'email' => 'required|string|email|max:255|unique:ms_user', 
            'password' => 'required|string|min:8|confirmed', 
        ], 
        [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'no_telp.required'   => 'Nomor WhatsApp wajib diisi.', // Pesan kustom WA
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // 2. Simpan data ke database (ms_user)
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp, // 🚨 SEKARANG SUDAH DISIMPAN KE DB
            'password' => Hash::make($request->password), 
            'id_role' => 3, // Otomatis jadikan Customer
        ]);

        // 3. Langsung login-kan setelah sukses daftar
        Auth::login($user);

        // 4. Arahkan ke Dashboard Customer
        return redirect()->route('dashboard')->with('success', 'Pendaftaran akun berhasil!');
    }

    /**
     * FR02: Proses Login dengan Multi-Role Redirect
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ],
        [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // 2. Cek kecocokan email dan password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = auth()->user();

            // 3. LOGIKA REDIRECT BERDASARKAN ROLE
            if ($user->id_role == 1) {
                return redirect()->route('superadmin.dashboard'); 
            } 
            elseif ($user->id_role == 2) {
                return redirect()->route('admin.dashboard'); 
            } 
            else {
                return redirect()->route('dashboard'); 
            }
        }

        // 4. Jika Gagal Login
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * FR02: Proses Logout
     */
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
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
                Auth::login($user);
            } else {
                $user = User::create([
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'id_role' => 3, 
                    'password' => Hash::make(uniqid()), 
                ]);
                Auth::login($user);
            }

            if ($user->id_role == 1) {
                return redirect()->route('superadmin.dashboard'); 
            } elseif ($user->id_role == 2) {
                return redirect()->route('admin.dashboard'); 
            } else {
                return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil!'); 
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google.']);
        }
    }
}