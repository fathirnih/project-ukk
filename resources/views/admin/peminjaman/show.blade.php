@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('header')
<i class="fas fa-eye me-2"></i>Detail Peminjaman
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.peminjaman.index') }}" class="admin-back-link">
        <i class="fas fa-arrow-left me-1"></i>Kembali ke daftar
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card admin-card mb-3">
            <div class="card-header admin-card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="admin-index-overview mb-3">
                    <div class="admin-mini-stat">
                        <span>Kode</span>
                        <strong>{{ $peminjaman->kode }}</strong>
                    </div>
                    <div class="admin-mini-stat">
                        <span>Total Buku</span>
                        <strong>{{ $peminjaman->detailPeminjamans->count() }}</strong>
                    </div>
                </div>
                <div class="admin-detail-info">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold">Anggota</td>
                        <td>: {{ $peminjaman->anggota->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NISN</td>
                        <td>: {{ $peminjaman->anggota->nisn }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kelas</td>
                        <td>: {{ $peminjaman->anggota->kelas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tgl Pinjam</td>
                        <td>: {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tgl Kembali</td>
                        <td>: {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status Pinjam</td>
                        <td>: 
                            @if($peminjaman->status_pinjam == 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>
                            @elseif($peminjaman->status_pinjam == 'disetujui')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Disetujui</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @if($peminjaman->catatan)
                    <tr>
                        <td class="fw-bold">Catatan</td>
                        <td>: {{ $peminjaman->catatan }}</td>
                    </tr>
                    @endif
                    @if($peminjaman->catatan_penolakan)
                    <tr>
                        <td class="fw-bold text-danger">Alasan</td>
                        <td class="text-danger">: {{ $peminjaman->catatan_penolakan }}</td>
                    </tr>
                    @endif
                </table>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        @if($peminjaman->status_pinjam == 'pending')
            <div class="card admin-card mb-3">
                <div class="card-header admin-card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.peminjaman.setujui', $peminjaman->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 admin-action-btn" onclick="return confirm('Setujui peminjaman ini?')">
                                <i class="fas fa-check me-1"></i> Setujui Peminjaman
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger w-100 admin-action-btn" data-bs-toggle="modal" data-bs-target="#tolakModal">
                            <i class="fas fa-times me-1"></i> Tolak Peminjaman
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if($peminjaman->status_pinjam == 'disetujui' && $peminjaman->pengembalian && $peminjaman->pengembalian->status == 'pending_admin')
            <div class="card admin-card mb-3">
                <div class="card-header admin-card-header">
                    <h5 class="mb-0"><i class="fas fa-undo me-2"></i>Konfirmasi Pengembalian</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.peminjaman.konfirmasi-kembali', $peminjaman->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 admin-action-btn" onclick="return confirm('Konfirmasi bahwa buku telah dikembalikan?')">
                                <i class="fas fa-check-double me-1"></i> Konfirmasi Pengembalian
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger w-100 admin-action-btn" data-bs-toggle="modal" data-bs-target="#tolakKembaliModal">
                            <i class="fas fa-times me-1"></i> Tolak Pengembalian
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card admin-card">
            <div class="card-header admin-card-header">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Buku yang Dipinjam</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 admin-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Buku</th>
                                <th class="text-center" style="width: 80px;">Jumlah</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman->detailPeminjamans as $index => $detail)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detail->buku->cover && file_exists(public_path('covers/' . $detail->buku->cover)))
                                                <img src="{{ asset('covers/' . $detail->buku->cover) }}"
                                                     alt="{{ $detail->buku->judul }}"
                                                     class="img-thumbnail me-2 admin-detail-book-cover">
                                            @else
                                                <div class="bg-secondary text-white me-2 d-flex align-items-center justify-content-center admin-detail-book-fallback">
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
                                    <td class="text-center">
                                        @if($detail->status == 'dipinjam')
                                            <span class="badge bg-primary"><i class="fas fa-book me-1"></i>Dipinjam</span>
                                        @elseif($detail->status == 'dikembalikan')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Kembali</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>Hilang</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
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
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Tolak Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.peminjaman.tolak', $peminjaman->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan_penolakan" class="form-label admin-form-label">Alasan Penolakan <span class="text-danger">*</span></label>
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

<!-- Modal Tolak Pengembalian -->
<div class="modal fade" id="tolakKembaliModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Tolak Pengembalian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.peminjaman.tolak-kembali', $peminjaman->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan_penolakan_kembali" class="form-label admin-form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_penolakan_kembali" name="catatan_penolakan" rows="3" required placeholder="Masukkan alasan penolakan pengembalian..."></textarea>
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
