<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPengembalianController extends Controller
{
    // Dashboard pengembalian
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Pengembalian::with('peminjaman.anggota', 'peminjaman.detailPeminjamans.buku');
        
        if ($status === 'pending') {
            $query->where('status', 'pending_admin');
        } elseif ($status === 'ditolak') {
            $query->where('status', 'ditolak');
        } elseif ($status === 'selesai') {
            $query->where('status', 'selesai');
        }
        
        $pengembalian = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.pengembalian.index', compact('pengembalian', 'status'));
    }

    // Detail pengembalian
    public function show($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.anggota', 'peminjaman.detailPeminjamans.buku')->findOrFail($id);
        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    // Konfirmasi pengembalian
    public function konfirmasi($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.detailPeminjamans')->findOrFail($id);
        $peminjaman = $pengembalian->peminjaman;
        
        DB::transaction(function () use ($peminjaman, $pengembalian) {
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
            
            $pengembalian->update([
                'status' => 'selesai',
                'tanggal_dikembalikan' => now()->toDateString(),
            ]);
        });

        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian telah dikonfirmasi.');
    }

    // Tolak pengembalian
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan' => 'required',
        ]);

        $pengembalian = Pengembalian::findOrFail($id);
        
        $pengembalian->update([
            'status' => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);

        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian telah ditolak.');
    }
}
