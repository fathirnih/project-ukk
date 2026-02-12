<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function showLogin()
    {
        // Check if already logged in as admin
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Admin!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function dashboard()
    {
        $totalBuku = \App\Models\Buku::count();
        $totalAnggota = \App\Models\Anggota::count();
        $totalKategori = \App\Models\Kategori::count();
        
        return view('admin.dashboard', compact('totalBuku', 'totalAnggota', 'totalKategori'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear member session as well
        Session::forget(['anggota_id', 'anggota_nama', 'anggota_nisn']);
        
        return redirect('/');
    }
}
