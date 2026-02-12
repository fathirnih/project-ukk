<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Session;

class PengembalianController extends Controller
{
    // Halaman index pengembalian (daftar buku yang dipinjam)
    public function index()
    {
        $anggota = null;
        if (Session::has('anggota_id')) {
            $anggota = Anggota::find(Session::get('anggota_id'));
        }
        
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Tampilkan buku yang sedang dipinjam (belum ada pengembalian)
        $pinjamans = Peminjaman::with('detailPeminjamans.buku')
                            ->where('anggota_id', $anggota->id)
                            ->where('status_pinjam', 'disetujui')
                            ->whereDoesntHave('pengembalian')
                            ->get();
        
        return view('pengembalian.index', compact('anggota', 'pinjamans'));
    }

    // Riwayat pengembalian anggota
    public function riwayat()
    {
        $anggota = null;
        if (Session::has('anggota_id')) {
            $anggota = Anggota::find(Session::get('anggota_id'));
        }
        
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Tampilkan riwayat pengembalian
        $riwayat = Pengembalian::with('peminjaman.detailPeminjamans.buku')
                            ->whereHas('peminjaman', function ($query) use ($anggota) {
                                $query->where('anggota_id', $anggota->id);
                            })
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        $response = response()->view('pengembalian.riwayat', compact('anggota', 'riwayat'));
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        return $response;
    }
}
