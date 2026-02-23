<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Support\SimpleXlsxExporter;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $validated['tanggal_awal'] ?? null;
        $tanggalAkhir = $validated['tanggal_akhir'] ?? null;

        $peminjamanQuery = Peminjaman::query();
        $pengembalianQuery = Pengembalian::query();

        if ($tanggalAwal && $tanggalAkhir) {
            $peminjamanQuery->whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir]);
            $pengembalianQuery->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir]);
        }

        $totalPeminjaman = $peminjamanQuery->count();
        $totalPengembalian = $pengembalianQuery->count();
        $totalDenda = (clone $pengembalianQuery)->sum('total_denda');

        return view('admin.laporan.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalPeminjaman',
            'totalPengembalian',
            'totalDenda'
        ));
    }

    public function exportPeminjaman(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir] = $this->resolveDateRange($request);

        $peminjamans = Peminjaman::with(['anggota', 'detailPeminjamans.buku', 'pengembalian'])
            ->whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal_pinjam')
            ->orderBy('id')
            ->get();

        $rows = [[
            'No',
            'ID Peminjaman',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Nama Anggota',
            'NISN',
            'Daftar Buku',
            'Total Item',
            'Status Peminjaman',
            'Status Pengembalian',
            'Hari Terlambat',
            'Total Denda',
        ]];

        foreach ($peminjamans as $index => $peminjaman) {
            $daftarBuku = $peminjaman->detailPeminjamans
                ->map(function ($detail) {
                    $judul = $detail->buku->judul ?? 'Buku tidak ditemukan';
                    return "{$judul} (x{$detail->jumlah})";
                })
                ->implode(', ');

            $rows[] = [
                $index + 1,
                $peminjaman->id,
                optional($peminjaman->tanggal_pinjam)->format('Y-m-d'),
                optional($peminjaman->tanggal_kembali)->format('Y-m-d'),
                $peminjaman->anggota->nama ?? '-',
                $peminjaman->anggota->nisn ?? '-',
                $daftarBuku !== '' ? $daftarBuku : '-',
                (string) $peminjaman->detailPeminjamans->sum('jumlah'),
                $peminjaman->status_pinjam,
                $peminjaman->pengembalian->status ?? '-',
                (string) ($peminjaman->pengembalian->hari_terlambat ?? 0),
                (string) ($peminjaman->pengembalian->total_denda ?? 0),
            ];
        }

        $filename = "laporan-peminjaman-{$tanggalAwal}-sampai-{$tanggalAkhir}.xlsx";

        return SimpleXlsxExporter::download($filename, $rows, 'Laporan Peminjaman');
    }

    public function exportPengembalian(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir] = $this->resolveDateRange($request);

        $pengembalians = Pengembalian::with(['peminjaman.anggota', 'peminjaman.detailPeminjamans.buku'])
            ->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal_pengajuan')
            ->orderBy('id')
            ->get();

        $rows = [[
            'No',
            'ID Pengembalian',
            'ID Peminjaman',
            'Tanggal Pengajuan',
            'Tanggal Dikembalikan',
            'Nama Anggota',
            'NISN',
            'Daftar Buku',
            'Status Pengembalian',
            'Hari Terlambat',
            'Denda Per Hari',
            'Total Denda',
            'Catatan Penolakan',
        ]];

        foreach ($pengembalians as $index => $pengembalian) {
            $peminjaman = $pengembalian->peminjaman;
            $daftarBuku = $peminjaman?->detailPeminjamans
                ->map(function ($detail) {
                    $judul = $detail->buku->judul ?? 'Buku tidak ditemukan';
                    return "{$judul} (x{$detail->jumlah})";
                })
                ->implode(', ');

            $rows[] = [
                $index + 1,
                $pengembalian->id,
                $peminjaman->id ?? '-',
                optional($pengembalian->tanggal_pengajuan)->format('Y-m-d'),
                optional($pengembalian->tanggal_dikembalikan)->format('Y-m-d') ?? '-',
                $peminjaman?->anggota?->nama ?? '-',
                $peminjaman?->anggota?->nisn ?? '-',
                $daftarBuku !== '' ? $daftarBuku : '-',
                $pengembalian->status,
                (string) ($pengembalian->hari_terlambat ?? 0),
                (string) ($pengembalian->denda_per_hari ?? 0),
                (string) ($pengembalian->total_denda ?? 0),
                $pengembalian->catatan_penolakan ?: '-',
            ];
        }

        $filename = "laporan-pengembalian-{$tanggalAwal}-sampai-{$tanggalAkhir}.xlsx";

        return SimpleXlsxExporter::download($filename, $rows, 'Laporan Pengembalian');
    }

    private function resolveDateRange(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $validated['tanggal_awal'] ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $validated['tanggal_akhir'] ?? now()->toDateString();

        return [$tanggalAwal, $tanggalAkhir];
    }
}
