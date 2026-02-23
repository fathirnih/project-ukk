@extends('layouts.admin')

@section('title', 'Laporan - Admin')
@section('header', 'Laporan')

@section('content')
<section class="admin-report-page">
    <div class="admin-page-intro mb-4">
        <h6><i class="fas fa-file-excel me-2"></i>Export Laporan Excel</h6>
        <p>Pilih rentang tanggal, lalu unduh laporan peminjaman atau pengembalian dalam format .xlsx.</p>
    </div>

    <div class="admin-index-overview mb-4">
        <div class="admin-mini-stat">
            <span>Total Peminjaman</span>
            <strong>{{ $totalPeminjaman }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Total Pengembalian</span>
            <strong>{{ $totalPengembalian }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Total Denda</span>
            <strong>Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="card admin-card mb-4">
        <div class="card-header admin-card-header">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Periode</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end" id="laporanFilterForm">
                <div class="col-md-4">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control @error('tanggal_awal') is-invalid @enderror laporan-filter-input" value="{{ $tanggalAwal }}">
                    @error('tanggal_awal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control @error('tanggal_akhir') is-invalid @enderror laporan-filter-input" value="{{ $tanggalAkhir }}">
                    @error('tanggal_akhir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4"></div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card admin-card h-100">
                <div class="card-header admin-card-header">
                    <h5 class="mb-0"><i class="fas fa-book-reader me-2"></i>Laporan Peminjaman</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-3">Berisi data transaksi peminjaman, anggota, status, detail buku, dan ringkasan denda.</p>
                    <div class="mt-auto">
                        <a
                            href="{{ route('admin.laporan.peminjaman.export', array_filter(['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir])) }}"
                            class="btn btn-success w-100 admin-action-btn"
                        >
                            <i class="fas fa-file-download me-1"></i> Export .xlsx
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card admin-card h-100">
                <div class="card-header admin-card-header">
                    <h5 class="mb-0"><i class="fas fa-undo me-2"></i>Laporan Pengembalian</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-3">Berisi data pengembalian, status konfirmasi, keterlambatan, dan total denda per transaksi.</p>
                    <div class="mt-auto">
                        <a
                            href="{{ route('admin.laporan.pengembalian.export', array_filter(['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir])) }}"
                            class="btn btn-success w-100 admin-action-btn"
                        >
                            <i class="fas fa-file-download me-1"></i> Export .xlsx
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    (function () {
        const form = document.getElementById('laporanFilterForm');
        if (!form) return;

        const inputs = form.querySelectorAll('.laporan-filter-input');
        const submitForm = () => form.requestSubmit();

        inputs.forEach((input) => {
            input.addEventListener('change', submitForm);
        });

    })();
</script>
@endsection
