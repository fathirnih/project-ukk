@extends($app_layout ?? 'layouts.app')

@section('title', 'Koleksi Buku - Perpustakaan Digital')

@section('content')
@php
    $kategoriAktif = \App\Models\Kategori::count();
    $stokTersedia = $buku->where('jumlah', '>', 0)->count();
@endphp

<div class="container py-4">
    <div class="collection-hero mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="fw-bold mb-2">
                    <i class="fas fa-book-open me-2"></i>Koleksi Buku
                </h1>
                <p class="mb-0 opacity-75">Temukan buku yang Anda cari dari koleksi kami yang terus diperbarui.</p>
                <div class="collection-hero-tags mt-3">
                    <span><i class="fas fa-circle-check me-1"></i>Kurasi Berkala</span>
                    <span><i class="fas fa-compass me-1"></i>Navigasi Mudah</span>
                    <span><i class="fas fa-box-archive me-1"></i>Stok Realtime</span>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="collection-hero-stat">
                            <span>Total Ditampilkan</span>
                            <strong>{{ $buku->count() }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="collection-hero-stat">
                            <span>Kategori Aktif</span>
                            <strong>{{ $kategoriAktif }}</strong>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="collection-hero-stat">
                            <span>Buku Siap Dipinjam</span>
                            <strong>{{ $stokTersedia }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($buku->count() > 0)
        <div class="row g-4">
            @foreach($buku as $item)
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 collection-book-card">
                        @if($item->cover && file_exists(public_path('covers/' . $item->cover)))
                            <img src="{{ asset('covers/' . $item->cover) }}" class="card-img-top collection-book-cover" alt="{{ $item->judul }}">
                        @else
                            <div class="collection-book-cover bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="fas fa-book fa-4x"></i>
                            </div>
                        @endif
                        <div class="card-body pb-2">
                            <h6 class="card-title fw-bold text-truncate" title="{{ $item->judul }}">{{ $item->judul }}</h6>
                            <p class="card-text small text-muted mb-2">{{ $item->pengarang }}</p>
                            <p class="small text-muted mb-2"><i class="fas fa-building me-1"></i>{{ $item->penerbit ?? 'Penerbit belum diisi' }}</p>
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                @if($item->kategori)
                                    <span class="badge text-bg-info">{{ $item->kategori->nama }}</span>
                                @else
                                    <span class="badge text-bg-secondary">Umum</span>
                                @endif
                                @if(($item->jumlah ?? 0) > 0)
                                    <span class="badge text-bg-success">{{ $item->jumlah }} tersedia</span>
                                @else
                                    <span class="badge text-bg-danger">Kosong</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="{{ route('buku.detail', $item->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-light rounded-4 collection-empty">
            <i class="fas fa-book fa-5x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">Belum Ada Buku</h4>
            <p class="text-muted mb-4">Koleksi buku sedang dalam pengembangan.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Kembali ke Home
            </a>
        </div>
    @endif
</div>
@endsection
