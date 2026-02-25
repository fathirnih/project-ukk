<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Support\SimpleXlsxExporter;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir] = $this->validatedFilterDate($request);
        $anggotaId = $this->validatedAnggotaId($request);
        [$statusPinjam, $statusPengembalian] = $this->validatedStatusFilter($request);
        [$bukuId, $kategoriId] = $this->validatedBookFilter($request);
        $anggotas = Anggota::orderBy('nama')->get(['id', 'nama', 'nisn']);
        $bukus = Buku::orderBy('judul')->get(['id', 'judul']);
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);

        $peminjamanQuery = Peminjaman::query();
        $pengembalianQuery = Pengembalian::query();

        $this->applyDateFilter($peminjamanQuery, 'tanggal_pinjam', $tanggalAwal, $tanggalAkhir);
        $this->applyDateFilter($pengembalianQuery, 'tanggal_pengajuan', $tanggalAwal, $tanggalAkhir);
        $this->applyAnggotaFilter($peminjamanQuery, $anggotaId);
        $this->applyAnggotaFilter($pengembalianQuery, $anggotaId);
        $this->applyStatusFilter($peminjamanQuery, $statusPinjam, $statusPengembalian);
        $this->applyStatusFilter($pengembalianQuery, $statusPinjam, $statusPengembalian);
        $this->applyBookCategoryFilter($peminjamanQuery, $bukuId, $kategoriId);
        $this->applyBookCategoryFilter($pengembalianQuery, $bukuId, $kategoriId);

        $totalPeminjaman = $peminjamanQuery->count();
        $totalPengembalian = $pengembalianQuery->count();
        $totalDenda = (clone $pengembalianQuery)->sum('total_denda');

        return view('admin.laporan.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'anggotaId',
            'anggotas',
            'statusPinjam',
            'statusPengembalian',
            'bukuId',
            'kategoriId',
            'bukus',
            'kategoris',
            'totalPeminjaman',
            'totalPengembalian',
            'totalDenda'
        ));
    }

    public function exportPeminjaman(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir] = $this->validatedFilterDate($request);
        $anggotaId = $this->validatedAnggotaId($request);
        [$statusPinjam, $statusPengembalian] = $this->validatedStatusFilter($request);
        [$bukuId, $kategoriId] = $this->validatedBookFilter($request);

        $query = Peminjaman::with(['anggota', 'detailPeminjamans.buku', 'pengembalian']);
        $this->applyDateFilter($query, 'tanggal_pinjam', $tanggalAwal, $tanggalAkhir);
        $this->applyAnggotaFilter($query, $anggotaId);
        $this->applyStatusFilter($query, $statusPinjam, $statusPengembalian);
        $this->applyBookCategoryFilter($query, $bukuId, $kategoriId);

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
        $anggotaId = $this->validatedAnggotaId($request);
        [$statusPinjam, $statusPengembalian] = $this->validatedStatusFilter($request);
        [$bukuId, $kategoriId] = $this->validatedBookFilter($request);

        $query = Pengembalian::with(['peminjaman.anggota', 'peminjaman.detailPeminjamans.buku']);
        $this->applyDateFilter($query, 'tanggal_pengajuan', $tanggalAwal, $tanggalAkhir);
        $this->applyAnggotaFilter($query, $anggotaId);
        $this->applyStatusFilter($query, $statusPinjam, $statusPengembalian);
        $this->applyBookCategoryFilter($query, $bukuId, $kategoriId);

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

    private function validatedAnggotaId(Request $request): ?int
    {
        $validated = $request->validate([
            'anggota_id' => 'nullable|integer|exists:anggota,id',
        ]);

        return isset($validated['anggota_id']) ? (int) $validated['anggota_id'] : null;
    }

    private function validatedStatusFilter(Request $request): array
    {
        $validated = $request->validate([
            'status_pinjam' => 'nullable|in:pending,disetujui,ditolak',
            'status_pengembalian' => 'nullable|in:pending_admin,selesai,ditolak',
        ]);

        return [
            $validated['status_pinjam'] ?? null,
            $validated['status_pengembalian'] ?? null,
        ];
    }

    private function validatedBookFilter(Request $request): array
    {
        $validated = $request->validate([
            'buku_id' => 'nullable|integer|exists:buku,id',
            'kategori_id' => 'nullable|integer|exists:kategori,id',
        ]);

        return [
            isset($validated['buku_id']) ? (int) $validated['buku_id'] : null,
            isset($validated['kategori_id']) ? (int) $validated['kategori_id'] : null,
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

    private function applyAnggotaFilter($query, ?int $anggotaId): void
    {
        if (!$anggotaId) {
            return;
        }

        if ($query->getModel() instanceof Peminjaman) {
            $query->where('anggota_id', $anggotaId);
            return;
        }

        if ($query->getModel() instanceof Pengembalian) {
            $query->whereHas('peminjaman', function ($q) use ($anggotaId) {
                $q->where('anggota_id', $anggotaId);
            });
        }
    }

    private function applyStatusFilter($query, ?string $statusPinjam, ?string $statusPengembalian): void
    {
        if ($query->getModel() instanceof Peminjaman) {
            if ($statusPinjam) {
                $query->where('status_pinjam', $statusPinjam);
            }
            return;
        }

        if ($query->getModel() instanceof Pengembalian) {
            if ($statusPengembalian) {
                $query->where('status', $statusPengembalian);
            }
        }
    }

    private function applyBookCategoryFilter($query, ?int $bukuId, ?int $kategoriId): void
    {
        if (!$bukuId && !$kategoriId) {
            return;
        }

        $bookConstraint = function ($detailQuery) use ($bukuId, $kategoriId) {
            if ($bukuId) {
                $detailQuery->where('buku_id', $bukuId);
            }

            if ($kategoriId) {
                $detailQuery->whereHas('buku', function ($bukuQuery) use ($kategoriId) {
                    $bukuQuery->where('kategori_id', $kategoriId);
                });
            }
        };

        if ($query->getModel() instanceof Peminjaman) {
            $query->whereHas('detailPeminjamans', $bookConstraint);
            return;
        }

        if ($query->getModel() instanceof Pengembalian) {
            $query->whereHas('peminjaman.detailPeminjamans', $bookConstraint);
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
