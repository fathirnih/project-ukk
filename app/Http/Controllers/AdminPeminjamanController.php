<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPeminjamanController extends Controller
{
    // Dashboard peminjaman
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = trim((string) $request->get('q', ''));
        $statPending = Peminjaman::where('status_pinjam', 'pending')->count();
        $statDisetujui = Peminjaman::where('status_pinjam', 'disetujui')->count();
        $statDitolak = Peminjaman::where('status_pinjam', 'ditolak')->count();
        $statSelesai = Peminjaman::where('status_pinjam', 'disetujui')
            ->whereHas('detailPeminjamans')
            ->whereDoesntHave('detailPeminjamans', function ($q) {
                $q->where('status', '!=', 'dikembalikan');
            })
            ->count();
        
        $query = Peminjaman::with('anggota', 'detailPeminjamans.buku', 'pengembalian');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                if (is_numeric($search)) {
                    $subQuery->orWhere('id', (int) $search);
                }

                $subQuery->orWhere('status_pinjam', 'like', "%{$search}%")
                    ->orWhere('tanggal_pinjam', 'like', "%{$search}%")
                    ->orWhere('tanggal_kembali', 'like', "%{$search}%")
                    ->orWhereHas('anggota', function ($anggotaQuery) use ($search) {
                        $anggotaQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    });
            });
        }
        
        if ($status === 'pending') {
            $query->where('status_pinjam', 'pending');
        } elseif ($status === 'ditolak') {
            $query->where('status_pinjam', 'ditolak');
        } elseif ($status === 'menunggu_kembali') {
            $query->where('status_pinjam', 'disetujui')->whereDoesntHave('pengembalian');
        } elseif ($status === 'menunggu_kembali_admin') {
            $query->whereHas('pengembalian', function ($q) {
                $q->where('status', 'pending_admin');
            });
        } elseif ($status === 'selesai') {
            $query->where('status_pinjam', 'disetujui')
                ->whereHas('detailPeminjamans')
                ->whereDoesntHave('detailPeminjamans', function ($q) {
                    $q->where('status', '!=', 'dikembalikan');
                });
        }
        
        $peminjamans = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        return view('admin.peminjaman.index', compact(
            'peminjamans',
            'status',
            'search',
            'statPending',
            'statDisetujui',
            'statDitolak',
            'statSelesai'
        ));
    }

    // Detail peminjaman
    public function show($id)
    {
        $peminjaman = Peminjaman::with('anggota', 'detailPeminjamans.buku', 'pengembalian')->findOrFail($id);
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

        return redirect()->route('admin.peminjaman.index', ['status' => 'disetujui'])->with('success', 'Peminjaman telah disetujui.');
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

        return redirect()->route('admin.peminjaman.index', ['status' => 'ditolak'])->with('success', 'Peminjaman telah ditolak.');
    }

    // Konfirmasi pengembalian (tabel terpisah)
    public function konfirmasiKembali($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjamans')->findOrFail($id);
        $pengembalian = $peminjaman->pengembalian;
        
        if (!$pengembalian) {
            return redirect()->route('admin.pengembalian.index')->with('error', 'Pengembalian tidak ditemukan.');
        }
        
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

    // Tolak pengembalian (tabel terpisah)
    public function tolakKembali(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan' => 'required',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $pengembalian = $peminjaman->pengembalian;
        
        if (!$pengembalian) {
            return redirect()->route('admin.pengembalian.index')->with('error', 'Pengembalian tidak ditemukan.');
        }

        $pengembalian->update([
            'status' => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);

        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian telah ditolak.');
    }
}
