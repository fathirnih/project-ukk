@extends('layouts.admin')

@section('title', 'Detail Pengembalian')

@section('header')
<i class="fas fa-eye me-2"></i>Detail Pengembalian
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary">
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

<div class="row">
    <div class="col-md-4">
        <div class="card custom-card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pengembalian</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold" style="width: 100px;">Kode</td>
                        <td>: #{{ str_pad($pengembalian->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Anggota</td>
                        <td>: {{ $pengembalian->peminjaman->anggota->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NISN</td>
                        <td>: {{ $pengembalian->peminjaman->anggota->nisn }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tgl Pengajuan</td>
                        <td>: {{ $pengembalian->tanggal_pengajuan->format('d/m/Y') }}</td>
                    </tr>
                    @if($pengembalian->tanggal_dikembalikan)
                    <tr>
                        <td class="fw-bold">Tgl Dikembalikan</td>
                        <td>: {{ $pengembalian->tanggal_dikembalikan->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-bold">Status</td>
                        <td>: 
                            @if($pengembalian->status == 'pending_admin')
                                <span class="badge bg-info text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>
                            @elseif($pengembalian->status == 'ditolak')
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>
                            @else
                                <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @if($pengembalian->catatan_penolakan)
                    <tr>
                        <td class="fw-bold text-danger">Alasan</td>
                        <td class="text-danger">: {{ $pengembalian->catatan_penolakan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        @if($pengembalian->status == 'pending_admin')
            <div class="card custom-card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.pengembalian.konfirmasi', $pengembalian->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Konfirmasi bahwa buku telah dikembalikan?')">
                                <i class="fas fa-check-double me-1"></i> Konfirmasi Pengembalian
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#tolakModal">
                            <i class="fas fa-times me-1"></i> Tolak Pengembalian
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card custom-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Buku yang Dikembalikan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Buku</th>
                                <th class="text-center" style="width: 80px;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengembalian->peminjaman->detailPeminjamans as $index => $detail)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detail->buku->cover && file_exists(public_path('storage/covers/' . $detail->buku->cover)))
                                                <img src="{{ asset('storage/covers/' . $detail->buku->cover) }}" 
                                                     alt="{{ $detail->buku->judul }}" 
                                                     class="img-thumbnail me-2"
                                                     style="width: 45px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 45px; height: 60px; font-size: 18px;">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $detail->buku->judul }}</div>
                                                <small class="text-muted">{{ $detail->buku->pengarang }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $detail->jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada buku</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Tolak Pengembalian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengembalian.tolak', $pengembalian->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_penolakan" name="catatan_penolakan" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
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
