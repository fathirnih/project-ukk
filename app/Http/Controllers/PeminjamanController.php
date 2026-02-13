<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PeminjamanController extends Controller
{
    // Halaman ajuan pinjam untuk anggota
    public function index(Request $request)
    {
        $anggota = null;
        if (Session::has('anggota_id')) {
            $anggota = Anggota::find(Session::get('anggota_id'));
        }
        
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $search = trim((string) $request->get('q', ''));
        $kategoriId = $request->get('kategori_id');
        $pengarang = trim((string) $request->get('pengarang', ''));
        $penerbit = trim((string) $request->get('penerbit', ''));

        $query = Buku::with('kategori')->where('jumlah', '>', 0);

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('judul', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%");
            });
        }

        if (!empty($kategoriId)) {
            $query->where('kategori_id', (int) $kategoriId);
        }

        if ($pengarang !== '') {
            $query->where('pengarang', $pengarang);
        }

        if ($penerbit !== '') {
            $query->where('penerbit', $penerbit);
        }

        $bukus = $query->orderBy('judul')->paginate(10)->withQueryString();

        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);
        $pengarangList = Buku::where('jumlah', '>', 0)
            ->whereNotNull('pengarang')
            ->where('pengarang', '!=', '')
            ->distinct()
            ->orderBy('pengarang')
            ->pluck('pengarang');
        $penerbitList = Buku::where('jumlah', '>', 0)
            ->whereNotNull('penerbit')
            ->where('penerbit', '!=', '')
            ->distinct()
            ->orderBy('penerbit')
            ->pluck('penerbit');
        
        return view('anggota.peminjaman.index', compact(
            'anggota',
            'bukus',
            'kategoris',
            'pengarangList',
            'penerbitList',
            'search',
            'kategoriId',
            'pengarang',
            'penerbit'
        ));
    }

    // Simpan ajuan pinjam
    public function store(Request $request)
    {
        $request->validate([
            'buku_ids' => 'required',
            'tanggal_kembali' => 'required|date|after:today',
        ]);

        $bukuIds = json_decode($request->buku_ids, true);
        $bukuIds = array_values(array_unique(array_map('intval', (array) $bukuIds)));
        
        if (empty($bukuIds)) {
            return redirect()->route('peminjaman.index')->with('error', 'Pilih minimal satu buku!');
        }

        $anggota = Anggota::find(Session::get('anggota_id'));
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        DB::transaction(function () use ($request, $anggota, $bukuIds) {
            $peminjaman = Peminjaman::create([
                'anggota_id' => $anggota->id,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali' => $request->tanggal_kembali,
                'status_pinjam' => 'pending',
                'catatan' => $request->catatan ?? null,
            ]);

            foreach ($bukuIds as $bukuId) {
                $buku = Buku::find($bukuId);
                $jumlah = isset($request->jumlah[$bukuId]) ? (int)$request->jumlah[$bukuId] : 1;
                
                if ($buku && $buku->jumlah >= $jumlah && $jumlah > 0) {
                    DetailPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'buku_id' => $bukuId,
                        'jumlah' => $jumlah,
                        'status' => 'dipinjam',
                    ]);
                }
            }
        });

        return redirect('/peminjaman/riwayat')->with('success', 'Ajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
    }

    // Anggota mengajukan pengembalian (tabel terpisah)
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
            return redirect()->route('peminjaman.riwayat')->with('error', 'Peminjaman tidak ditemukan atau belum disetujui.');
        }

        // Pastikan masih ada item yang belum dikembalikan.
        $masihDipinjam = $peminjaman->detailPeminjamans()->where('status', 'dipinjam')->exists();
        if (!$masihDipinjam) {
            return redirect()->route('peminjaman.riwayat')->with('error', 'Semua buku pada transaksi ini sudah dikembalikan.');
        }

        // Cegah duplikasi record pengembalian untuk satu transaksi peminjaman.
        if ($peminjaman->pengembalian) {
            if ($peminjaman->pengembalian->status == 'pending_admin') {
                return redirect()->route('peminjaman.riwayat')->with('error', 'Pengembalian sudah diajukan dan menunggu konfirmasi.');
            }

            if ($peminjaman->pengembalian->status == 'ditolak') {
                return redirect()->route('peminjaman.riwayat')->with('error', 'Pengembalian terakhir ditolak. Gunakan tombol "Ajukan Lagi".');
            }

            if ($peminjaman->pengembalian->status == 'selesai') {
                return redirect()->route('peminjaman.riwayat')->with('error', 'Pengembalian untuk transaksi ini sudah selesai.');
            }
        }

        // Buat record pengembalian baru
        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'pending_admin',
        ]);

        return redirect()->route('pengembalian.riwayat')->with('success', 'Ajuan pengembalian berhasil dikirim. Menunggu konfirmasi admin.');
    }

    // Riwayat peminjaman (termasuk pengembalian)
    public function riwayatPeminjaman()
    {
        $anggota = null;
        if (Session::has('anggota_id')) {
            $anggota = Anggota::find(Session::get('anggota_id'));
        }
        
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Tampilkan semua riwayat peminjaman
        $riwayat = Peminjaman::with('detailPeminjamans.buku', 'pengembalian')
                            ->where('anggota_id', $anggota->id)
                            ->whereIn('status_pinjam', ['pending', 'disetujui', 'ditolak'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        $response = response()->view('anggota.peminjaman.riwayat-peminjaman', compact('anggota', 'riwayat'));
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        return $response;
    }

    // Anggota mengajukan ulang peminjaman yang ditolak (update status, bukan buat baru)
    public function ajukanUlang($id)
    {
        $anggota = Anggota::find(Session::get('anggota_id'));
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $peminjaman = Peminjaman::where('anggota_id', $anggota->id)->find($id);
        
        if (!$peminjaman || $peminjaman->status_pinjam != 'ditolak') {
            return redirect()->route('peminjaman.riwayat')->with('error', 'Peminjaman tidak ditemukan atau tidak bisa diajukan ulang.');
        }

        // Update status peminjaman yang ditolak menjadi pending
        $peminjaman->update([
            'status_pinjam' => 'pending',
        ]);

        return redirect()->route('peminjaman.riwayat')->with('success', 'Peminjaman berhasil diajukan ulang! Menunggu persetujuan admin.');
    }

    // Anggota mengajukan ulang pengembalian yang ditolak (tabel terpisah)
    public function ajukanUlangPengembalian($id)
    {
        $anggota = Anggota::find(Session::get('anggota_id'));
        if (!$anggota) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cari pengembalian berdasarkan ID
        $pengembalian = Pengembalian::with('peminjaman')->find($id);
        
        if (!$pengembalian || !$pengembalian->peminjaman || $pengembalian->peminjaman->anggota_id != $anggota->id) {
            return redirect()->route('pengembalian.riwayat')->with('error', 'Pengembalian tidak ditemukan atau tidak bisa diajukan ulang.');
        }

        if ($pengembalian->status != 'ditolak') {
            return redirect()->route('pengembalian.riwayat')->with('error', 'Pengembalian tidak bisa diajukan ulang (status bukan ditolak).');
        }

        // Update status pengembalian yang ditolak menjadi pending_admin
        $pengembalian->update([
            'status' => 'pending_admin',
            'catatan_penolakan' => null,
        ]);

        return redirect()->route('pengembalian.riwayat')->with('success', 'Pengembalian berhasil diajukan ulang! Menunggu konfirmasi admin.');
    }
}
