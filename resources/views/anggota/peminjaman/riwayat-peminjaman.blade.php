@extends('layouts.member')

@section('title', 'Riwayat Peminjaman - Perpustakaan Digital')

@section('content')
<section class="member-page member-main-block">
    <div class="member-page-header">
        <h1><i class="fas fa-history me-2"></i>Riwayat Peminjaman</h1>
        <p>Pantau status semua peminjaman Anda di satu tempat.</p>
    </div>

    @if(!session('anggota_id'))
        <div class="alert alert-warning">
            Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu.
        </div>
    @else
        <div class="card member-section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Riwayat Peminjaman
                </h5>
            </div>
            <div class="card-body">
                @if($riwayat->count() > 0)
                    <div class="member-stack">
                        @foreach($riwayat as $peminjaman)
                            <article class="member-record-card">
                                <div class="member-record-head">
                                    <div>
                                        <div class="fw-bold">#{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="member-record-meta">
                                            <i class="fas fa-calendar-plus me-1"></i>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}
                                            <span class="mx-2">-</span>
                                            <i class="fas fa-calendar-check me-1"></i>{{ $peminjaman->tanggal_kembali->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        @if($peminjaman->status_pinjam == 'pending')
                                            <span class="badge bg-warning member-status-badge">Menunggu</span>
                                        @elseif($peminjaman->status_pinjam == 'disetujui')
                                            @if($peminjaman->status_kembali == 'pending')
                                                <span class="badge bg-success member-status-badge">Dipinjam</span>
                                            @elseif($peminjaman->status_kembali == 'pending_admin')
                                                <span class="badge bg-info member-status-badge">Mengembalikan</span>
                                            @elseif($peminjaman->status_kembali == 'ditolak')
                                                <span class="badge bg-danger member-status-badge">Ditolak</span>
                                            @else
                                                <span class="badge bg-secondary member-status-badge">Selesai</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger member-status-badge">Ditolak</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-3 align-items-start">
                                    <div class="col-lg-8">
                                        <div class="member-book-list">
                                            @foreach($peminjaman->detailPeminjamans as $detail)
                                                <div class="member-book-item">
                                                    @if($detail->buku->cover && file_exists(public_path('storage/covers/' . $detail->buku->cover)))
                                                        <img src="{{ asset('storage/covers/' . $detail->buku->cover) }}" alt="{{ $detail->buku->judul }}" class="member-book-cover">
                                                    @else
                                                        <div class="member-book-fallback bg-secondary text-white d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-book"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">{{ $detail->buku->judul }}</div>
                                                        <small class="text-primary">Qty: {{ $detail->jumlah }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="d-grid gap-2">
                                            @if($peminjaman->status_pinjam == 'pending')
                                                <span class="text-muted small">Menunggu...</span>
                                            @elseif($peminjaman->status_pinjam == 'disetujui' && $peminjaman->status_kembali == 'pending')
                                                <a href="/peminjaman/ajuan-kembali/{{ $peminjaman->id }}" 
                                                   class="btn btn-sm btn-warning member-action-button"
                                                   onclick="return confirm('Ajukan pengembalian buku?')">
                                                    <i class="fas fa-undo me-1"></i>Kembalikan
                                                </a>
                                            @elseif($peminjaman->status_pinjam == 'disetujui' && $peminjaman->status_kembali == 'ditolak')
                                                <a href="{{ route('peminjaman.ajukan-ulang-pengembalian', $peminjaman->id) }}" 
                                                   class="btn btn-sm btn-primary member-action-button"
                                                   onclick="return confirm('Ajukan pengembalian ulang?')">
                                                    <i class="fas fa-redo me-1"></i>Ajukan Lagi
                                                </a>
                                            @elseif($peminjaman->status_pinjam == 'ditolak')
                                                <a href="{{ route('peminjaman.ajukan-ulang', $peminjaman->id) }}" 
                                                   class="btn btn-sm btn-primary member-action-button"
                                                   onclick="return confirm('Ajukan peminjaman ulang dengan buku yang sama?')">
                                                    <i class="fas fa-redo me-1"></i>Ajukan Lagi
                                                </a>
                                            @elseif($peminjaman->status_pinjam == 'disetujui' && $peminjaman->status_kembali == 'pending_admin')
                                                <span class="text-muted small">Menunggu...</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $riwayat->links() }}
                    </div>
                @else
                    <div class="member-empty-state">
                        <i class="fas fa-history"></i>
                        <h5>Belum ada riwayat peminjaman</h5>
                        <p class="text-muted">Riwayat peminjaman akan muncul di sini.</p>
                        <a href="/peminjaman" class="btn btn-primary member-action-button">
                            <i class="fas fa-book-reader me-2"></i>Ajukan Peminjaman
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
