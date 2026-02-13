@extends('layouts.member')

@section('title', 'Pengembalian - Perpustakaan Digital')

@section('content')
<section class="member-page member-main-block">
    <div class="member-page-header">
        <h1><i class="fas fa-undo me-2"></i>Pengembalian Buku</h1>
        <p>Ajukan pengembalian untuk buku yang sedang Anda pinjam.</p>
    </div>

    @if(!session('anggota_id'))
        <div class="alert alert-warning">
            Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu.
        </div>
    @else
        <div class="card member-section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2"></i>Buku yang Sedang Dipinjam
                </h5>
            </div>
            <div class="card-body">
                @if($pinjamans->count() > 0)
                    <div class="member-stack">
                        @foreach($pinjamans as $peminjaman)
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
                                    <a href="{{ route('peminjaman.ajuan-kembali', $peminjaman->id) }}" 
                                       class="btn btn-sm btn-primary member-action-button"
                                       onclick="return confirm('Ajukan pengembalian buku ini?')">
                                        <i class="fas fa-undo me-1"></i>Ajukan Pengembalian
                                    </a>
                                </div>
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
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="member-empty-state">
                        <i class="fas fa-check-circle"></i>
                        <h5>Tidak ada buku yang dipinjam</h5>
                        <p class="text-muted">Anda tidak memiliki buku yang sedang dipinjam.</p>
                        <a href="/peminjaman" class="btn btn-primary member-action-button">
                            <i class="fas fa-book-reader me-2"></i>Ajuan Peminjaman
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
