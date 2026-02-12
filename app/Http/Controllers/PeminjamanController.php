<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Halaman ajuan pinjam untuk anggota
    public function index()
    {
        $anggota = null;
        if (Session::has('anggota_id')) {
            $anggota = Anggota::find(Session::get('anggota_id'));
        }
        
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $bukus = Buku::where('jumlah', '>', 0)->get();
        $riwayat = Peminjaman::with('detailPeminjamans.buku')
                            ->where('anggota_id', $anggota->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('peminjaman.index', compact('anggota', 'bukus', 'riwayat'));
    }

    // Simpan ajuan pinjam
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|array',
            'buku_id.*' => 'exists:bukus,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'tanggal_kembali' => 'required|date|after:today',
        ]);

        $anggota = Anggota::find(Session::get('anggota_id'));
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        DB::transaction(function () use ($request, $anggota) {
            $peminjaman = Peminjaman::create([
                'anggota_id' => $anggota->id,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali' => $request->tanggal_kembali,
                'status_pinjam' => 'pending',
                'status_kembali' => 'pending',
                'catatan' => $request->catatan ?? null,
            ]);

            foreach ($request->buku_id as $index => $buku_id) {
                $buku = Buku::find($buku_id);
                if ($buku && $buku->jumlah >= $request->jumlah[$index]) {
                    DetailPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'buku_id' => $buku_id,
                        'jumlah' => $request->jumlah[$index],
                        'status' => 'dipinjam',
                    ]);
                }
            }
        });

        return redirect()->route('peminjaman.index')->with('success', 'Ajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
    }

    // Anggota mengajukan pengembalian
    public function ajukanKembali($id)
    {
        $anggota = Anggota::find(Session::get('anggota_id'));
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $peminjaman = Peminjaman::where('anggota_id', $anggota->id)
                                ->where('id', $id)
                                ->where('status_pinjam', 'disetujui')
                                ->first();

        if (!$peminjaman) {
            return redirect()->route('peminjaman.index')->with('error', 'Peminjaman tidak ditemukan atau belum disetujui.');
        }

        $peminjaman->update(['status_kembali' => 'pending_admin']);

        return redirect()->route('peminjaman.index')->with('success', 'Ajuan pengembalian berhasil dikirim. Menunggu konfirmasi admin.');
    }
}
