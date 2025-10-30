<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // tampilkan form login -> jika sudah auth redirect sesuai role
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->is_admin
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }
        return view('auth.login');
    }

    // proses login (aman terhadap intended yang mengarah balik ke login/logout)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'Email atau password tidak cocok.'])->onlyInput('email');
        }

        // berhasil login
        $request->session()->regenerate();

        // ambil intended dan pastikan bukan login/logout
        $intended = session()->pull('url.intended', null);

        if ($intended) {
            $loginPath = parse_url(route('login', [], false), PHP_URL_PATH) ?: '/login';
            $logoutPath = parse_url(route('logout', [], false), PHP_URL_PATH) ?: '/logout';

            if (!str_contains($intended, $loginPath) && !str_contains($intended, $logoutPath)) {
                return redirect()->to($intended);
            }
        }

        // fallback role-based
        return Auth::user()->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }

    // logout (POST)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
