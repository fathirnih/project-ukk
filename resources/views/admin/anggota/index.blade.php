@extends('layouts.admin')

@section('title', 'Kelola Anggota - Admin')
@section('header', 'Kelola Anggota')

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-id-card me-2"></i>Manajemen Anggota</h6>
    <p>Atur data anggota, periksa kelengkapan profil, dan pantau akun terbaru.</p>
</div>

<div class="admin-index-overview mb-4">
    <div class="admin-mini-stat">
        <span>Total Anggota</span>
        <strong>{{ $totalAnggota }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Data Kelas Terisi</span>
        <strong>{{ $denganKelas }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Tanpa Alamat</span>
        <strong>{{ $tanpaAlamat }}</strong>
    </div>
</div>

<div class="admin-content-grid mb-4">
    <div class="admin-spotlight-card">
        <h6><i class="fas fa-bolt me-2"></i>Aksi Cepat</h6>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-success admin-action-btn">
                <i class="fas fa-user-plus me-2"></i>Tambah Anggota
            </a>
            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-primary admin-action-btn">
                <i class="fas fa-book-reader me-2"></i>Lihat Peminjaman
            </a>
        </div>
    </div>
    <div class="admin-spotlight-card">
        <h6><i class="fas fa-clock me-2"></i>Anggota Terbaru</h6>
        <div class="admin-spotlight-list">
            @foreach($anggota->sortByDesc('created_at')->take(3) as $item)
                <div class="admin-spotlight-item">
                    <span class="text-truncate">{{ $item->nama }}</span>
                    <small>{{ $item->kelas ?: 'Tanpa kelas' }}</small>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Daftar Anggota
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nisn }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kelas ?? '-' }}</td>
                            <td>{{ Str::limit($item->alamat ?? '-', 30) }}</td>
                            <td>
                                <a href="{{ route('admin.anggota.edit', $item->id) }}" class="btn btn-warning btn-sm admin-icon-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
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
                            <td colspan="6" class="text-center py-4">Tidak ada data anggota</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
