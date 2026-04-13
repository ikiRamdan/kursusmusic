<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login (Method yang tadi hilang/error)
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Logika login untuk Aplikasi Web
     */
    public function loginWeb(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah user aktif
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->with('error', 'Akun Anda telah dinonaktifkan.');
            }

            return redirect()->route($user->role . '.dashboard');

        return back()->with('error', 'Email atau password salah.');
    }
    
    }

    /**
     * Logika logout untuk Web & API
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Menghapus session agar aman
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logout success']);
        }

        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Logika login untuk API (Json Response)
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'message' => 'Login success',
                'role' => $user->role,
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}