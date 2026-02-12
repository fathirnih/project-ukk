@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('header')
    Detail Peminjaman
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td><strong>Kode</strong></td>
                        <td>: #{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Anggota</strong></td>
                        <td>: {{ $peminjaman->anggota->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>NISN</strong></td>
                        <td>: {{ $peminjaman->anggota->nisn }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pinjam</strong></td>
                        <td>: {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Kembali</strong></td>
                        <td>: {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status Pinjam</strong></td>
                        <td>: 
                            @if($peminjaman->status_pinjam == 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($peminjaman->status_pinjam == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Kembali</strong></td>
                        <td>: 
                            @if($peminjaman->status_kembali == 'pending')
                                <span class="badge bg-secondary">-</span>
                            @elseif($peminjaman->status_kembali == 'pending_admin')
                                <span class="badge bg-warning">Menunggu</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @if($peminjaman->catatan)
                    <tr>
                        <td><strong>Catatan</strong></td>
                        <td>: {{ $peminjaman->catatan }}</td>
                    </tr>
                    @endif
                    @if($peminjaman->catatan_penolakan)
                    <tr>
                        <td><strong>Alasan Penolakan</strong></td>
                        <td>: {{ $peminjaman->catatan_penolakan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        @if($peminjaman->status_pinjam == 'pending')
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.peminjaman.setujui', $peminjaman->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui peminjaman ini?')">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#tolakModal">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if($peminjaman->status_pinjam == 'disetujui' && $peminjaman->status_kembali == 'pending_admin')
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.peminjaman.konfirmasi-kembali', $peminjaman->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Konfirmasi pengembalian buku?')">
                            <i class="fas fa-undo"></i> Konfirmasi Pengembalian
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Buku yang Dipinjam</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman->detailPeminjamans as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $detail->buku->judul }}<br>
                                    <small class="text-muted">{{ $detail->buku->pengarang }}</small>
                                </td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>
                                    @if($detail->status == 'dipinjam')
                                        <span class="badge bg-primary">Dipinjam</span>
                                    @elseif($detail->status == 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-danger">Hilang</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.peminjaman.tolak', $peminjaman->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan_penolakan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_penolakan" name="catatan_penolakan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
