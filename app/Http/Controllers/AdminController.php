<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
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
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalKategori = Kategori::count();
        $totalDipinjam = DetailPeminjaman::where('status', 'dipinjam')->sum('jumlah');

        $pendingPeminjaman = Peminjaman::where('status_pinjam', 'pending')->count();
        $pendingPengembalian = Pengembalian::where('status', 'pending_admin')->count();
        $ditolakPengembalian = Pengembalian::where('status', 'ditolak')->count();

        $latestBooks = Buku::with('kategori')->latest()->take(6)->get();
        $lowStockBooks = Buku::where('jumlah', '<=', 3)->orderBy('jumlah')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'totalKategori',
            'totalDipinjam',
            'pendingPeminjaman',
            'pendingPengembalian',
            'ditolakPengembalian',
            'latestBooks',
            'lowStockBooks'
        ));
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
