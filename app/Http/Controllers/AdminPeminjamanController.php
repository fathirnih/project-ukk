<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPeminjamanController extends Controller
{
    // Dashboard peminjaman
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Peminjaman::with('anggota', 'detailPeminjamans.buku');
        
        if ($status === 'pending') {
            $query->where('status_pinjam', 'pending');
        } elseif ($status === 'menunggu_kembali') {
            $query->where('status_pinjam', 'disetujui')->where('status_kembali', 'pending');
        } elseif ($status === 'menunggu_kembali_admin') {
            $query->where('status_kembali', 'pending_admin');
        } elseif ($status === 'selesai') {
            $query->where('status_kembali', 'selesai');
        }
        
        $peminjamans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.peminjaman.index', compact('peminjamans', 'status'));
    }

    // Detail peminjaman
    public function show($id)
    {
        $peminjaman = Peminjaman::with('anggota', 'detailPeminjamans.buku')->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    // Setujui peminjaman
    public function setujui($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjamans')->findOrFail($id);
        
        DB::transaction(function () use ($peminjaman) {
            foreach ($peminjaman->detailPeminjamans as $detail) {
                $buku = Buku::find($detail->buku_id);
                if ($buku && $buku->jumlah >= $detail->jumlah) {
                    $buku->decrement('jumlah', $detail->jumlah);
                }
            }
            
            $peminjaman->update([
                'status_pinjam' => 'disetujui',
                'catatan_penolakan' => null,
            ]);
        });

        return redirect()->route('admin.peminjaman.show', $id)->with('success', 'Peminjaman telah disetujui.');
    }

    // Tolak peminjaman
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan' => 'required',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status_pinjam' => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);

        return redirect()->route('admin.peminjaman.show', $id)->with('success', 'Peminjaman telah ditolak.');
    }

    // Konfirmasi pengembalian
    public function konfirmasiKembali($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjamans')->findOrFail($id);
        
        DB::transaction(function () use ($peminjaman) {
            foreach ($peminjaman->detailPeminjamans as $detail) {
                $buku = Buku::find($detail->buku_id);
                if ($buku) {
                    $buku->increment('jumlah', $detail->jumlah);
                }
                
                $detail->update([
                    'status' => 'dikembalikan',
                    'tanggal_dikembalikan' => now()->toDateString(),
                ]);
            }
            
            $peminjaman->update(['status_kembali' => 'selesai']);
        });

        return redirect()->route('admin.peminjaman.show', $id)->with('success', 'Pengembalian telah dikonfirmasi.');
    }
}
