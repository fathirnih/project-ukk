@extends('layouts.admin')

@section('title', 'Kelola Buku - Admin')
@section('header', 'Kelola Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Buku
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-book me-2"></i>Daftar Buku
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Cover</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th>Penerbit</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buku as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($item->cover && file_exists(public_path('covers/' . $item->cover)))
                                    <img src="{{ asset('covers/' . $item->cover) }}" alt="{{ $item->judul }}" style="width: 50px; height: 70px; object-fit: cover;" class="rounded">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 70px;">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->judul }}</td>
                            <td>{{ $item->pengarang }}</td>
                            <td>
                                @if($item->kategori)
                                    <span class="badge bg-info">{{ $item->kategori->nama }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->penerbit ?? '-' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>
                                <a href="{{ route('admin.buku.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.buku.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Tidak ada data buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
