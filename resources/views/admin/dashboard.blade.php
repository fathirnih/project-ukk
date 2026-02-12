@extends('layouts.admin')

@section('title', 'Dashboard - Admin')
@section('header', 'Dashboard Admin')

@section('content')
<section class="admin-dashboard-page">
    <div class="admin-dashboard-hero mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <span class="admin-dashboard-chip mb-2 d-inline-flex">Panel Admin</span>
                <h3 class="mb-2 fw-bold">Ringkasan Perpustakaan Digital</h3>
                <p class="mb-0 opacity-75">
                    Pantau peminjaman, pengembalian, dan stok buku dari satu dashboard.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="admin-hero-mini-grid">
                    <div class="admin-hero-mini">
                        <span>Perlu Verifikasi</span>
                        <strong>{{ $pendingPeminjaman + $pendingPengembalian }}</strong>
                    </div>
                    <div class="admin-hero-mini">
                        <span>Ditolak</span>
                        <strong>{{ $ditolakPengembalian }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card admin-stat-card primary h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">
                        <i class="fas fa-book me-2"></i>Total Buku
                    </h6>
                    <h2 class="fw-bold mb-1">{{ $totalBuku }}</h2>
                    <small class="opacity-75">Koleksi aktif saat ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card admin-stat-card success h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">
                        <i class="fas fa-users me-2"></i>Total Anggota
                    </h6>
                    <h2 class="fw-bold mb-1">{{ $totalAnggota }}</h2>
                    <small class="opacity-75">Akun anggota terdaftar</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card admin-stat-card warning h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">
                        <i class="fas fa-tags me-2"></i>Total Kategori
                    </h6>
                    <h2 class="fw-bold mb-1">{{ $totalKategori }}</h2>
                    <small class="opacity-75">Kategori buku aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card admin-stat-card danger h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">
                        <i class="fas fa-bookmark me-2"></i>Buku Dipinjam
                    </h6>
                    <h2 class="fw-bold mb-1">{{ $totalDipinjam }}</h2>
                    <small class="opacity-75">Unit sedang dipinjam</small>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-feature-wrap mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-bolt me-2"></i>Fitur Cepat</h5>
            <small class="text-muted">Akses modul utama</small>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('admin.buku.create') }}" class="admin-feature-card">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Buku</span>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('admin.buku.index') }}" class="admin-feature-card">
                    <i class="fas fa-book"></i>
                    <span>Manajemen Buku</span>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('admin.anggota.index') }}" class="admin-feature-card">
                    <i class="fas fa-users"></i>
                    <span>Kelola Anggota</span>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('admin.kategori.index') }}" class="admin-feature-card">
                    <i class="fas fa-tags"></i>
                    <span>Kelola Kategori</span>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('admin.peminjaman.index') }}" class="admin-feature-card">
                    <i class="fas fa-book-reader"></i>
                    <span>Verifikasi Peminjaman</span>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('admin.pengembalian.index') }}" class="admin-feature-card">
                    <i class="fas fa-undo"></i>
                    <span>Konfirmasi Pengembalian</span>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('admin.anggota.create') }}" class="admin-feature-card">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah Anggota</span>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('home') }}" target="_blank" class="admin-feature-card">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Lihat Website</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card admin-card h-100">
                <div class="card-header admin-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Buku Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 admin-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestBooks as $index => $buku)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-semibold">{{ $buku->judul }}</td>
                                        <td>{{ $buku->pengarang }}</td>
                                        <td>
                                            @if($buku->kategori)
                                                <span class="badge bg-info">{{ $buku->kategori->nama }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $buku->jumlah }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada buku</td>
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
                        <a href="{{ route('admin.peminjaman.index') }}" class="admin-action-item">
                            <div>
                                <strong>Peminjaman Pending</strong>
                                <div class="text-muted small">Menunggu verifikasi admin</div>
                            </div>
                            <span class="badge bg-warning text-dark">{{ $pendingPeminjaman }}</span>
                        </a>
                        <a href="{{ route('admin.pengembalian.index') }}" class="admin-action-item">
                            <div>
                                <strong>Pengembalian Pending</strong>
                                <div class="text-muted small">Butuh konfirmasi pengembalian</div>
                            </div>
                            <span class="badge bg-info">{{ $pendingPengembalian }}</span>
                        </a>
                        <a href="{{ route('admin.pengembalian.index') }}" class="admin-action-item">
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
