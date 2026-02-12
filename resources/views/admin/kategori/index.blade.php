@extends('layouts.admin')

@section('title', 'Kelola Kategori - Admin')
@section('header', 'Kelola Kategori')

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-sitemap me-2"></i>Struktur Kategori</h6>
    <p>Gunakan kategori untuk menjaga koleksi tetap rapi dan memudahkan pencarian buku.</p>
</div>

<div class="admin-index-overview mb-4">
    <div class="admin-mini-stat">
        <span>Total Kategori</span>
        <strong>{{ $totalKategori }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Buku Terkategori</span>
        <strong>{{ $totalBukuTerkategori }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Kategori Kosong</span>
        <strong>{{ $kategoriKosong }}</strong>
    </div>
</div>

<div class="admin-content-grid mb-4">
    <div class="admin-spotlight-card">
        <h6><i class="fas fa-bolt me-2"></i>Aksi Cepat</h6>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-success admin-action-btn">
                <i class="fas fa-plus me-2"></i>Tambah Kategori
            </a>
            <a href="{{ route('admin.buku.index') }}" class="btn btn-outline-primary admin-action-btn">
                <i class="fas fa-book me-2"></i>Lihat Buku
            </a>
        </div>
    </div>
    <div class="admin-spotlight-card">
        <h6><i class="fas fa-fire me-2"></i>Kategori Terbanyak</h6>
        <div class="admin-spotlight-list">
            @foreach($kategori->sortByDesc('buku_count')->take(3) as $item)
                <div class="admin-spotlight-item">
                    <span class="text-truncate">{{ $item->nama }}</span>
                    <small>{{ $item->buku_count }} buku</small>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0">
            <i class="fas fa-tags me-2"></i>Daftar Kategori
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Keterangan</th>
                        <th>Jumlah Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ Str::limit($item->keterangan ?? '-', 50) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->buku_count }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.kategori.edit', $item->id) }}" class="btn btn-warning btn-sm admin-icon-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm admin-icon-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
