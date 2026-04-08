<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    // 1. Menampilkan Form Lupa Sandi (Input Email)
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Mengirim Link Reset ke Email
    public function sendEmail(Request $request)
    {
        // 🚨 UPDATE: 'exists:ms_user,email' karena tabel kita ms_user
        $request->validate([
            'email' => 'required|email|exists:ms_user,email'
        ], [
            'email.exists' => 'Alamat email tidak terdaftar di sistem kami.'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => 'Link reset kata sandi telah dikirim ke email Anda!'])
                    : back()->withErrors(['email' => 'Gagal mengirim link reset sandi.']);
    }

    // 3. Menampilkan Form Reset Sandi Baru
    public function resetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // 4. Memproses Perubahan Kata Sandi Baru
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:ms_user,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Menggunakan forceFill untuk update password terenkripsi
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', 'Kata sandi berhasil diubah! Silakan login kembali.')
                    : back()->withErrors(['email' => [__($status)]]);
    }
}