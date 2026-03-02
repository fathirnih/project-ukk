<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DemoTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $anggotaId = 1;
        $anggotaExists = DB::table('anggota')->where('id', $anggotaId)->exists();
        $bukuIds = DB::table('buku')->where('jumlah', '>', 0)->orderBy('id')->pluck('id')->all();

        if (!$anggotaExists || empty($bukuIds)) {
            $this->command?->warn('DemoTransaksiSeeder dilewati: anggota id=1 atau data buku belum tersedia.');
            return;
        }

        DB::transaction(function () use ($anggotaId, $bukuIds) {
            $demoIds = DB::table('peminjamans')
                ->where('catatan', 'like', '[DEMO]%')
                ->pluck('id');

            if ($demoIds->isNotEmpty()) {
                DB::table('peminjamans')->whereIn('id', $demoIds)->delete();
            }

            $now = now();
            $bukuIndex = 0;
            $dendaPerHari = (int) config('perpustakaan.denda_per_hari', 1000);

            $nextBukuId = function () use (&$bukuIndex, $bukuIds): int {
                $id = $bukuIds[$bukuIndex % count($bukuIds)];
                $bukuIndex++;
                return $id;
            };

            $insertPeminjaman = function (
                Carbon $tanggalPinjam,
                Carbon $tanggalKembali,
                string $statusPinjam,
                string $catatan,
                ?string $catatanPenolakan = null
            ) use ($now, $anggotaId): int {
                return DB::table('peminjamans')->insertGetId([
                    'anggota_id' => $anggotaId,
                    'tanggal_pinjam' => $tanggalPinjam->toDateString(),
                    'tanggal_kembali' => $tanggalKembali->toDateString(),
                    'status_pinjam' => $statusPinjam,
                    'catatan' => $catatan,
                    'catatan_penolakan' => $catatanPenolakan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            };

            $insertDetail = function (
                int $peminjamanId,
                int $bukuId,
                string $status = 'dipinjam',
                ?Carbon $tanggalDikembalikan = null
            ) use ($now): void {
                DB::table('detail_peminjamans')->insert([
                    'peminjaman_id' => $peminjamanId,
                    'buku_id' => $bukuId,
                    'jumlah' => 1,
                    'status' => $status,
                    'tanggal_dikembalikan' => $tanggalDikembalikan?->toDateString(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            };

            $pendingId = $insertPeminjaman(
                now()->subDays(1),
                now()->addDays(6),
                'pending',
                '[DEMO] Peminjaman menunggu konfirmasi admin'
            );
            $insertDetail($pendingId, $nextBukuId());

            $disetujuiId = $insertPeminjaman(
                now()->subDays(3),
                now()->addDays(4),
                'disetujui',
                '[DEMO] Peminjaman disetujui admin'
            );
            $insertDetail($disetujuiId, $nextBukuId());

            $ditolakPinjamId = $insertPeminjaman(
                now()->subDays(2),
                now()->addDays(5),
                'ditolak',
                '[DEMO] Peminjaman ditolak admin',
                'Melebihi batas maksimal peminjaman aktif.'
            );
            $insertDetail($ditolakPinjamId, $nextBukuId());

            $kembaliPendingId = $insertPeminjaman(
                now()->subDays(7),
                now()->subDays(1),
                'disetujui',
                '[DEMO] Pengembalian menunggu konfirmasi admin'
            );
            $insertDetail($kembaliPendingId, $nextBukuId());
            DB::table('pengembalians')->insert([
                'peminjaman_id' => $kembaliPendingId,
                'tanggal_pengajuan' => now()->toDateString(),
                'tanggal_dikembalikan' => null,
                'status' => 'pending_admin',
                'hari_terlambat' => 0,
                'denda_per_hari' => $dendaPerHari,
                'total_denda' => 0,
                'catatan_penolakan' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $kembaliDitolakId = $insertPeminjaman(
                now()->subDays(8),
                now()->subDays(2),
                'disetujui',
                '[DEMO] Pengembalian ditolak admin'
            );
            $insertDetail($kembaliDitolakId, $nextBukuId());
            DB::table('pengembalians')->insert([
                'peminjaman_id' => $kembaliDitolakId,
                'tanggal_pengajuan' => now()->subDay()->toDateString(),
                'tanggal_dikembalikan' => null,
                'status' => 'ditolak',
                'hari_terlambat' => 0,
                'denda_per_hari' => $dendaPerHari,
                'total_denda' => 0,
                'catatan_penolakan' => 'Buku yang dikembalikan belum lengkap.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $kembaliSelesaiTanpaDendaId = $insertPeminjaman(
                now()->subDays(10),
                now()->subDays(3),
                'disetujui',
                '[DEMO] Pengembalian selesai tanpa denda'
            );
            $tanggalPengajuanTepatWaktu = now()->subDays(3);
            $insertDetail(
                $kembaliSelesaiTanpaDendaId,
                $nextBukuId(),
                'dikembalikan',
                $tanggalPengajuanTepatWaktu
            );
            DB::table('pengembalians')->insert([
                'peminjaman_id' => $kembaliSelesaiTanpaDendaId,
                'tanggal_pengajuan' => $tanggalPengajuanTepatWaktu->toDateString(),
                'tanggal_dikembalikan' => $tanggalPengajuanTepatWaktu->toDateString(),
                'status' => 'selesai',
                'hari_terlambat' => 0,
                'denda_per_hari' => $dendaPerHari,
                'total_denda' => 0,
                'catatan_penolakan' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $kembaliSelesaiDendaId = $insertPeminjaman(
                now()->subDays(14),
                now()->subDays(7),
                'disetujui',
                '[DEMO] Pengembalian selesai kena denda'
            );
            $tanggalPengajuanTerlambat = now()->subDays(4);
            $hariTerlambat = 3;
            $insertDetail(
                $kembaliSelesaiDendaId,
                $nextBukuId(),
                'dikembalikan',
                $tanggalPengajuanTerlambat
            );
            DB::table('pengembalians')->insert([
                'peminjaman_id' => $kembaliSelesaiDendaId,
                'tanggal_pengajuan' => $tanggalPengajuanTerlambat->toDateString(),
                'tanggal_dikembalikan' => $tanggalPengajuanTerlambat->toDateString(),
                'status' => 'selesai',
                'hari_terlambat' => $hariTerlambat,
                'denda_per_hari' => $dendaPerHari,
                'total_denda' => $hariTerlambat * $dendaPerHari,
                'catatan_penolakan' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        $this->command?->info('DemoTransaksiSeeder selesai.');
    }
}
