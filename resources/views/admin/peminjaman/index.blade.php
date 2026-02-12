@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')

@section('header')
<i class="fas fa-book-reader me-2"></i>Kelola Peminjaman
@endsection

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-arrows-rotate me-2"></i>Workflow Peminjaman</h6>
    <p>Setujui atau tolak peminjaman berdasarkan ketersediaan buku dan riwayat anggota.</p>
</div>

<div class="admin-index-overview mb-4">
    <div class="admin-mini-stat">
        <span>Menunggu</span>
        <strong>{{ $statPending }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Disetujui</span>
        <strong>{{ $statDisetujui }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Ditolak</span>
        <strong>{{ $statDitolak }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Selesai</span>
        <strong>{{ $statSelesai }}</strong>
    </div>
</div>

<div class="admin-content-grid mb-4">
    <div class="admin-spotlight-card">
        <h6><i class="fas fa-list-check me-2"></i>Prioritas Hari Ini</h6>
        <div class="admin-spotlight-list">
            <div class="admin-spotlight-item"><span>Permintaan menunggu</span><small>{{ $statPending }}</small></div>
            <div class="admin-spotlight-item"><span>Butuh tindak lanjut</span><small>{{ $statDisetujui }}</small></div>
        </div>
    </div>
    <div class="admin-spotlight-card">
        <h6><i class="fas fa-link me-2"></i>Pintasan</h6>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-primary admin-action-btn" href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}">Pending</a>
            <a class="btn btn-outline-primary admin-action-btn" href="{{ route('admin.peminjaman.index', ['status' => 'disetujui']) }}">Disetujui</a>
            <a class="btn btn-outline-primary admin-action-btn" href="{{ route('admin.pengembalian.index', ['status' => 'pending']) }}">Pengembalian</a>
        </div>
    </div>
</div>

<div class="mb-4">
    <ul class="nav nav-tabs admin-tabs border-0">
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
            <a class="nav-link {{ $status == 'ditolak' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'ditolak']) }}">
                <i class="fas fa-times-circle me-1"></i> Ditolak
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'selesai' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'selesai']) }}">
                <i class="fas fa-check-double me-1"></i> Selesai
            </a>
        </li>
    </ul>
</div>

<div class="card admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 admin-table">
                <thead>
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
                                <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-primary admin-icon-btn" title="Lihat Detail">
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
@endsection
