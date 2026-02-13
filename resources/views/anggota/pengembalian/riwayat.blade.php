@extends('layouts.member')

@section('title', 'Riwayat Pengembalian - Perpustakaan Digital')

@section('content')
<section class="member-page member-main-block">
    <div class="member-page-header">
        <h1><i class="fas fa-undo me-2"></i>Riwayat Pengembalian</h1>
        <p>Lihat status proses pengembalian buku Anda.</p>
    </div>

    @if(!session('anggota_id'))
        <div class="alert alert-warning">
            Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu.
        </div>
    @else
        <div class="card member-section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Riwayat Pengembalian
                </h5>
            </div>
            <div class="card-body">
                @if($riwayat->count() > 0)
                    <div class="member-stack">
                        @foreach($riwayat as $pengembalian)
                            <article class="member-record-card">
                                <div class="member-record-head">
                                    <div>
                                        <div class="fw-bold">#{{ str_pad($pengembalian->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="member-record-meta">
                                            <i class="fas fa-calendar-check me-1"></i>{{ $pengembalian->peminjaman->tanggal_kembali->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        @if($pengembalian->status == 'pending_admin')
                                            <span class="badge bg-info member-status-badge">Menunggu</span>
                                        @elseif($pengembalian->status == 'ditolak')
                                            <span class="badge bg-danger member-status-badge">Ditolak</span>
                                        @else
                                            <span class="badge bg-success member-status-badge">Selesai</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-8">
                                        <div class="member-book-list">
                                            @foreach($pengembalian->peminjaman->detailPeminjamans as $detail)
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
                                            @if($pengembalian->status == 'selesai')
                                                <div class="small text-muted">Total Denda</div>
                                                <div class="fw-semibold text-danger">
                                                    Rp {{ number_format($pengembalian->total_denda ?? 0, 0, ',', '.') }}
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $pengembalian->hari_terlambat ?? 0 }} hari x Rp {{ number_format($pengembalian->denda_per_hari ?? 0, 0, ',', '.') }}
                                                </div>
                                            @endif
                                            @if($pengembalian->status == 'pending_admin')
                                                <span class="text-muted small">Menunggu...</span>
                                            @elseif($pengembalian->status == 'ditolak')
                                                <a href="{{ route('pengembalian.ajukan-ulang', $pengembalian->id) }}" 
                                                   class="btn btn-sm btn-primary member-action-button"
                                                   onclick="return confirm('Ajukan pengembalian ulang?')">
                                                    <i class="fas fa-redo me-1"></i>Ajukan Lagi
                                                </a>
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
                        <i class="fas fa-undo"></i>
                        <h5>Belum ada riwayat pengembalian</h5>
                        <p class="text-muted">Riwayat pengembalian akan muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
