<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Session;

class PengembalianController extends Controller
{
    // Halaman pengembalian gabungan: ajuan + riwayat
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

        $riwayat = Pengembalian::with('peminjaman.detailPeminjamans.buku')
                            ->whereHas('peminjaman', function ($query) use ($anggota) {
                                $query->where('anggota_id', $anggota->id);
                            })
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        return view('anggota.pengembalian.index', compact('anggota', 'pinjamans', 'riwayat'));
    }

    // Backward compatibility: route lama diarahkan ke halaman gabungan
    public function riwayat()
    {
        return redirect()->route('pengembalian.index');
    }
}
