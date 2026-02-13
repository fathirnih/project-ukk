@extends('layouts.admin')

@section('title', 'Dashboard - Admin')
@section('header', 'Dashboard Admin')

@section('content')
<section class="admin-dashboard-page">
    <div class="admin-page-intro mb-4">
        <h6><i class="fas fa-chart-line me-2"></i>Ringkasan Operasional</h6>
        <p>Pantau antrian verifikasi, kondisi stok, dan aktivitas koleksi terbaru.</p>
    </div>

    <div class="admin-index-overview mb-4">
        <div class="admin-mini-stat">
            <span>Total Buku</span>
            <strong>{{ $totalBuku }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Total Anggota</span>
            <strong>{{ $totalAnggota }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Total Kategori</span>
            <strong>{{ $totalKategori }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Unit Dipinjam</span>
            <strong>{{ $totalDipinjam }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Peminjaman Pending</span>
            <strong>{{ $pendingPeminjaman }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Peminjaman Ditolak</span>
            <strong>{{ $ditolakPeminjaman }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Pengembalian Pending</span>
            <strong>{{ $pendingPengembalian }}</strong>
        </div>
        <div class="admin-mini-stat">
            <span>Pengembalian Ditolak</span>
            <strong>{{ $ditolakPengembalian }}</strong>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card admin-card admin-dashboard-books h-100">
                <div class="card-header admin-card-header admin-dashboard-books-head">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Buku Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 admin-table admin-dashboard-books-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Kategori</th>
                                    <th class="text-center" style="width: 90px;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestBooks as $index => $buku)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="fw-semibold">
                                            <div class="admin-book-title">{{ $buku->judul }}</div>
                                        </td>
                                        <td>{{ $buku->pengarang }}</td>
                                        <td>
                                            @if($buku->kategori)
                                                <span class="badge admin-badge-soft-info">{{ $buku->kategori->nama }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $buku->jumlah <= 3 ? 'bg-warning text-dark' : 'bg-success' }}">{{ $buku->jumlah }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada buku</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card admin-card h-100">
                <div class="card-header admin-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Perlu Tindakan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="admin-action-list">
                        <a href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}" class="admin-action-item">
                            <div>
                                <strong>Peminjaman Pending</strong>
                                <div class="text-muted small">Menunggu verifikasi admin</div>
                            </div>
                            <span class="badge bg-warning text-dark">{{ $pendingPeminjaman }}</span>
                        </a>
                        <a href="{{ route('admin.peminjaman.index', ['status' => 'ditolak']) }}" class="admin-action-item">
                            <div>
                                <strong>Peminjaman Ditolak</strong>
                                <div class="text-muted small">Perlu ditinjau ulang</div>
                            </div>
                            <span class="badge bg-danger">{{ $ditolakPeminjaman }}</span>
                        </a>
                        <a href="{{ route('admin.pengembalian.index', ['status' => 'pending']) }}" class="admin-action-item">
                            <div>
                                <strong>Pengembalian Pending</strong>
                                <div class="text-muted small">Butuh konfirmasi pengembalian</div>
                            </div>
                            <span class="badge bg-info">{{ $pendingPengembalian }}</span>
                        </a>
                        <a href="{{ route('admin.pengembalian.index', ['status' => 'ditolak']) }}" class="admin-action-item">
                            <div>
                                <strong>Pengembalian Ditolak</strong>
                                <div class="text-muted small">Perlu ditinjau ulang</div>
                            </div>
                            <span class="badge bg-danger">{{ $ditolakPengembalian }}</span>
                        </a>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Stok Menipis</h6>
                    <div class="admin-low-stock">
                        @forelse($lowStockBooks as $buku)
                            <div class="admin-low-stock-item">
                                <span class="text-truncate">{{ $buku->judul }}</span>
                                <span class="badge {{ $buku->jumlah <= 1 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    {{ $buku->jumlah }}
                                </span>
                            </div>
                        @empty
                            <div class="text-muted small">Semua stok buku aman.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
