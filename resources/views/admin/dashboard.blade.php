@extends('layouts.admin')

@section('title', 'Dashboard - Admin')
@section('header', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-book me-2"></i>Total Buku
                </h5>
                <h2 class="fw-bold">{{ \App\Models\Buku::count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-users me-2"></i>Total Anggota
                </h5>
                <h2 class="fw-bold">{{ \App\Models\Anggota::count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-tags me-2"></i>Total Kategori
                </h5>
                <h2 class="fw-bold">{{ \App\Models\Kategori::count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-bookmark me-2"></i>Buku Dipinjam
                </h5>
                <h2 class="fw-bold">0</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.buku.create') }}" class="btn btn-primary w-100 py-3">
            <i class="fas fa-plus-circle me-2"></i>Tambah Buku
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.buku.index') }}" class="btn btn-info text-white w-100 py-3">
            <i class="fas fa-list me-2"></i>Daftar Buku
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.anggota.create') }}" class="btn btn-success w-100 py-3">
            <i class="fas fa-user-plus me-2"></i>Tambah Anggota
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary w-100 py-3">
            <i class="fas fa-users me-2"></i>Daftar Anggota
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-book me-2"></i>Buku Terbaru
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th>Penerbit</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Buku::latest()->take(5)->get() as $index => $buku)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->pengarang }}</td>
                            <td>
                                @if($buku->kategori)
                                    <span class="badge bg-info">{{ $buku->kategori->nama }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $buku->penerbit ?? '-' }}</td>
                            <td>{{ $buku->jumlah }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
