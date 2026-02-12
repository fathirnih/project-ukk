@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')

@section('header')
<i class="fas fa-book-reader me-2"></i>Kelola Peminjaman
@endsection

@section('content')
<div class="mb-4">
    <ul class="nav nav-tabs custom-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}">
                <i class="fas fa-list me-1"></i> Semua
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}">
                <i class="fas fa-clock me-1"></i> Menunggu Persetujuan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'disetujui' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'disetujui']) }}">
                <i class="fas fa-check-circle me-1"></i> Sedang Dipinjam
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'selesai' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'selesai']) }}">
                <i class="fas fa-check-double me-1"></i> Selesai
            </a>
        </li>
    </ul>
</div>

<div class="card custom-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Anggota</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th class="text-center">Status Pinjam</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $peminjaman)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $peminjaman->anggota->nama }}</div>
                                <small class="text-muted">{{ $peminjaman->anggota->nisn }}</small>
                            </td>
                            <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>
                                {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}
                                @if($peminjaman->tanggal_kembali < now()->toDateString() && $peminjaman->status_pinjam == 'disetujui' && !$peminjaman->pengembalian)
                                    <span class="badge bg-danger ms-1">Terlambat</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($peminjaman->status_pinjam == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                @elseif($peminjaman->status_pinjam == 'disetujui')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada data peminjaman</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $peminjamans->links() }}
</div>

<style>
    .custom-tabs .nav-link {
        color: #495057;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        margin-right: 5px;
        padding: 8px 16px;
    }
    .custom-tabs .nav-link:hover {
        background-color: #e9ecef;
    }
    .custom-tabs .nav-link.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>
@endsection
