@extends('layouts.admin')

@section('title', 'Konfirmasi Pengembalian')

@section('header')
<i class="fas fa-undo me-2"></i>Konfirmasi Pengembalian
@endsection

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-clipboard-check me-2"></i>Workflow Pengembalian</h6>
    <p>Validasi pengembalian buku anggota dan pastikan stok kembali ter-update dengan benar.</p>
</div>

<div class="admin-index-overview mb-4">
    <div class="admin-mini-stat">
        <span>Menunggu</span>
        <strong>{{ $statPending }}</strong>
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
        <h6><i class="fas fa-triangle-exclamation me-2"></i>Perhatian</h6>
        <div class="admin-spotlight-list">
            <div class="admin-spotlight-item"><span>Menunggu verifikasi</span><small>{{ $statPending }}</small></div>
            <div class="admin-spotlight-item"><span>Pengembalian ditolak</span><small>{{ $statDitolak }}</small></div>
        </div>
    </div>
    <div class="admin-spotlight-card">
        <h6><i class="fas fa-link me-2"></i>Pintasan</h6>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary admin-action-btn">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('admin.pengembalian.index', ['status' => 'pending']) }}" class="btn btn-outline-primary admin-action-btn">Menunggu</a>
            <a href="{{ route('admin.pengembalian.index', ['status' => 'selesai']) }}" class="btn btn-outline-primary admin-action-btn">Selesai</a>
        </div>
    </div>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pengembalian</h5>
        <div>
            <a href="{{ route('admin.pengembalian.index', ['status' => 'pending']) }}" class="btn btn-sm btn-light {{ $status == 'pending' ? 'bg-white text-primary' : '' }}">
                Menunggu
            </a>
            <a href="{{ route('admin.pengembalian.index', ['status' => 'ditolak']) }}" class="btn btn-sm btn-light {{ $status == 'ditolak' ? 'bg-white text-primary' : '' }}">
                Ditolak
            </a>
            <a href="{{ route('admin.pengembalian.index', ['status' => 'selesai']) }}" class="btn btn-sm btn-light {{ $status == 'selesai' ? 'bg-white text-primary' : '' }}">
                Selesai
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($pengembalian->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px">No</th>
                            <th style="width: 100px">Kode</th>
                            <th>Anggota</th>
                            <th>Tgl Pengajuan</th>
                            <th style="100px">Status</th>
                            <th style="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengembalian as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $item->peminjaman->anggota->nama }}</td>
                                <td>{{ $item->tanggal_pengajuan->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($item->status == 'pending_admin')
                                        <span class="badge bg-info text-dark">Menunggu</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pengembalian.show', $item->id) }}" class="btn btn-sm btn-primary admin-icon-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $pengembalian->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-undo fa-4x text-muted mb-3"></i>
                <h5>Tidak ada data pengembalian</h5>
                <p class="text-muted">Belum ada pengembalian dengan status ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
