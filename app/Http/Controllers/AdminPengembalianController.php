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
        $search = trim((string) $request->get('q', ''));
        $statPending = Pengembalian::where('status', 'pending_admin')->count();
        $statDitolak = Pengembalian::where('status', 'ditolak')->count();
        $statSelesai = Pengembalian::where('status', 'selesai')->count();
        
        $query = Pengembalian::with('peminjaman.anggota', 'peminjaman.detailPeminjamans.buku');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                if (is_numeric($search)) {
                    $subQuery->orWhere('id', (int) $search);
                }

                $subQuery->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('tanggal_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('peminjaman.anggota', function ($anggotaQuery) use ($search) {
                        $anggotaQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    });
            });
        }
        
        if ($status === 'pending') {
            $query->where('status', 'pending_admin');
        } elseif ($status === 'ditolak') {
            $query->where('status', 'ditolak');
        } elseif ($status === 'selesai') {
            $query->where('status', 'selesai');
        }
        
        $pengembalian = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        return view('admin.pengembalian.index', compact(
            'pengembalian',
            'status',
            'search',
            'statPending',
            'statDitolak',
            'statSelesai'
        ));
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
