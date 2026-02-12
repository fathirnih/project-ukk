@extends('layouts.admin')

@section('title', 'Konfirmasi Pengembalian')

@section('header')
<i class="fas fa-undo me-2"></i>Konfirmasi Pengembalian
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
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
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
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
                                    <a href="{{ route('admin.pengembalian.show', $item->id) }}" class="btn btn-sm btn-primary">
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
