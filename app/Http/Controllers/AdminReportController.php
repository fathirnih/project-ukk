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
        [$tanggalAwal, $tanggalAkhir] = $this->validatedFilterDate($request);

        $peminjamanQuery = Peminjaman::query();
        $pengembalianQuery = Pengembalian::query();

        $this->applyDateFilter($peminjamanQuery, 'tanggal_pinjam', $tanggalAwal, $tanggalAkhir);
        $this->applyDateFilter($pengembalianQuery, 'tanggal_pengajuan', $tanggalAwal, $tanggalAkhir);

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
        [$tanggalAwal, $tanggalAkhir] = $this->validatedFilterDate($request);

        $query = Peminjaman::with(['anggota', 'detailPeminjamans.buku', 'pengembalian']);
        $this->applyDateFilter($query, 'tanggal_pinjam', $tanggalAwal, $tanggalAkhir);

        $peminjamans = $query
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

        [$labelMulai, $labelSampai] = $this->rangeLabel($tanggalAwal, $tanggalAkhir);
        $filename = "laporan-peminjaman-{$labelMulai}-sampai-{$labelSampai}.xlsx";

        return SimpleXlsxExporter::download($filename, $rows, 'Laporan Peminjaman');
    }

    public function exportPengembalian(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir] = $this->validatedFilterDate($request);

        $query = Pengembalian::with(['peminjaman.anggota', 'peminjaman.detailPeminjamans.buku']);
        $this->applyDateFilter($query, 'tanggal_pengajuan', $tanggalAwal, $tanggalAkhir);

        $pengembalians = $query
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

        [$labelMulai, $labelSampai] = $this->rangeLabel($tanggalAwal, $tanggalAkhir);
        $filename = "laporan-pengembalian-{$labelMulai}-sampai-{$labelSampai}.xlsx";

        return SimpleXlsxExporter::download($filename, $rows, 'Laporan Pengembalian');
    }

    private function validatedFilterDate(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
        ]);

        return [
            $validated['tanggal_awal'] ?? null,
            $validated['tanggal_akhir'] ?? null,
        ];
    }

    private function applyDateFilter($query, string $column, ?string $tanggalAwal, ?string $tanggalAkhir): void
    {
        if ($tanggalAwal && $tanggalAkhir) {
            $query->whereBetween($column, [$tanggalAwal, $tanggalAkhir]);
            return;
        }

        if ($tanggalAwal) {
            $query->whereBetween($column, [$tanggalAwal, now()->toDateString()]);
            return;
        }

        if ($tanggalAkhir) {
            $query->whereDate($column, '<=', $tanggalAkhir);
        }
    }

    private function rangeLabel(?string $tanggalAwal, ?string $tanggalAkhir): array
    {
        if ($tanggalAwal && $tanggalAkhir) {
            return [$tanggalAwal, $tanggalAkhir];
        }

        if ($tanggalAwal) {
            return [$tanggalAwal, now()->toDateString()];
        }

        if ($tanggalAkhir) {
            return ['awal-data', $tanggalAkhir];
        }

        return ['awal-data', 'sekarang'];
    }
}
