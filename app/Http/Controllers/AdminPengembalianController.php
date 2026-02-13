<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        // Gunakan tanggal_pengajuan untuk menghitung keterlambatan
        $tanggalPengajuan = Carbon::parse($pengembalian->tanggal_pengajuan)->startOfDay();
        $tanggalHarusKembali = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
        $hariTerlambat = $tanggalPengajuan->greaterThan($tanggalHarusKembali)
            ? $tanggalHarusKembali->diffInDays($tanggalPengajuan)
            : 0;
        $dendaPerHari = (int) config('perpustakaan.denda_per_hari', 1000);
        $totalDenda = $hariTerlambat * $dendaPerHari;
        
        DB::transaction(function () use ($peminjaman, $pengembalian, $hariTerlambat, $dendaPerHari, $totalDenda) {
            foreach ($peminjaman->detailPeminjamans as $detail) {
                $buku = Buku::find($detail->buku_id);
                if ($buku) {
                    $buku->increment('jumlah', $detail->jumlah);
                }
                
                $detail->update([
                    'status' => 'dikembalikan',
                    'tanggal_dikembalikan' => $peminjaman->tanggal_kembali,
                ]);
            }
            
            $pengembalian->update([
                'status' => 'selesai',
                'hari_terlambat' => $hariTerlambat,
                'denda_per_hari' => $dendaPerHari,
                'total_denda' => $totalDenda,
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

    // Create - Tampilkan form tambah
    public function create()
    {
        $peminjamans = Peminjaman::where('status_pinjam', 'disetujui')
            ->with('anggota')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('admin.pengembalian.create', compact('peminjamans'));
    }

    // Store - Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'tanggal_pengajuan' => 'required|date',
        ]);
        
        $peminjaman = Peminjaman::find($request->peminjaman_id);
        $tanggalPengajuan = Carbon::parse($request->tanggal_pengajuan)->startOfDay();
        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
        
        $hariTerlambat = $request->status == 'selesai' && $tanggalPengajuan->greaterThan($tanggalKembali)
            ? $tanggalKembali->diffInDays($tanggalPengajuan)
            : ($request->status == 'selesai' ? 0 : ($request->hari_terlambat ?? 0));
        
        $dendaPerHari = (int) ($request->denda_per_hari ?? config('perpustakaan.denda_per_hari', 1000));
        $totalDenda = $request->status == 'selesai' ? $hariTerlambat * $dendaPerHari : ($request->total_denda ?? 0);
        
        Pengembalian::create([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status' => $request->status,
            'hari_terlambat' => $hariTerlambat,
            'denda_per_hari' => $dendaPerHari,
            'total_denda' => $totalDenda,
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);
        
        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian berhasil ditambahkan.');
    }

    // Edit - Tampilkan form edit
    public function edit($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.anggota')->findOrFail($id);

        // Tampilkan peminjaman yang masih relevan untuk dipilih, termasuk peminjaman saat ini.
        $peminjamans = Peminjaman::with('anggota')
            ->where(function ($query) use ($pengembalian) {
                $query->where('status_pinjam', 'disetujui')
                    ->orWhere('id', $pengembalian->peminjaman_id);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.pengembalian.edit', compact('pengembalian', 'peminjamans'));
    }

    // Update - Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'tanggal_pengajuan' => 'required|date',
        ]);
        
        $pengembalian = Pengembalian::findOrFail($id);
        $peminjaman = Peminjaman::find($request->peminjaman_id);
        $tanggalPengajuan = Carbon::parse($request->tanggal_pengajuan)->startOfDay();
        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
        
        $hariTerlambat = $request->status == 'selesai' && $tanggalPengajuan->greaterThan($tanggalKembali)
            ? $tanggalKembali->diffInDays($tanggalPengajuan)
            : ($request->status == 'selesai' ? 0 : ($request->hari_terlambat ?? 0));
        
        $dendaPerHari = (int) ($request->denda_per_hari ?? config('perpustakaan.denda_per_hari', 1000));
        $totalDenda = $request->status == 'selesai' ? $hariTerlambat * $dendaPerHari : ($request->total_denda ?? 0);
        
        $pengembalian->update([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status' => $request->status,
            'hari_terlambat' => $hariTerlambat,
            'denda_per_hari' => $dendaPerHari,
            'total_denda' => $totalDenda,
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);
        
        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian berhasil diperbarui.');
    }

    // Destroy - Hapus data
    public function destroy($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();
        
        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian berhasil dihapus.');
    }

    // Recalculate denda untuk semua pengembalian yang sudah selesai
    public function recalculateDenda()
    {
        $pengembalians = Pengembalian::where('status', 'selesai')->with('peminjaman')->get();
        $count = 0;
        
        foreach ($pengembalians as $pengembalian) {
            $tanggalPengajuan = Carbon::parse($pengembalian->tanggal_pengajuan)->startOfDay();
            $tanggalKembali = Carbon::parse($pengembalian->peminjaman->tanggal_kembali)->startOfDay();
            
            $hariTerlambat = $tanggalPengajuan->greaterThan($tanggalKembali)
                ? $tanggalKembali->diffInDays($tanggalPengajuan)
                : 0;
            
            $dendaPerHari = (int) config('perpustakaan.denda_per_hari', 1000);
            $totalDenda = $hariTerlambat * $dendaPerHari;
            
            $pengembalian->update([
                'hari_terlambat' => $hariTerlambat,
                'total_denda' => $totalDenda,
            ]);
            
            $count++;
        }
        
        return redirect()->route('admin.pengembalian.index')->with('success', "Denda berhasil dihitung ulang untuk {$count} data.");
    }
}
