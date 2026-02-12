@extends($app_layout ?? 'layouts.app')

@section('title', 'Home - Perpustakaan Digital')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
                </h1>
                <p class="lead mb-4">Temukan koleksi buku terbaik dari berbagai genre dan penulis terkenal. Jelajahi dunia pengetahuan tanpa batas.</p>
                <a href="{{ route('koleksi') }}" class="btn btn-light btn-lg fw-bold">
                    <i class="fas fa-search me-2"></i>Jelajahi Koleksi
                </a>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="fas fa-book-reader fa-8x opacity-25"></i>
            </div>
        </div>
    </div>
</section>

<!-- Featured Books -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-star text-warning me-2"></i>Buku Unggulan
        </h2>
        <a href="{{ route('koleksi') }}" class="btn btn-outline-primary">Lihat Semua</a>
    </div>

    @if($featuredBuku->count() > 0)
        <div class="row">
            @foreach($featuredBuku as $buku)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($buku->cover && file_exists(public_path('covers/' . $buku->cover)))
                            <img src="{{ asset('covers/' . $buku->cover) }}" class="card-img-top" alt="{{ $buku->judul }}" style="height: 250px; object-fit: cover;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center text-white" style="height: 250px;">
                                <i class="fas fa-book fa-4x"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $buku->judul }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $buku->pengarang }}</h6>
                            @if($buku->kategori)
                                <span class="badge bg-info mb-2">{{ $buku->kategori->nama }}</span>
                            @endif
                            <p class="card-text small text-muted">{{ Str::limit($buku->deskripsi, 60) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('buku.detail', $buku->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-light rounded">
            <i class="fas fa-book fa-4x text-muted mb-3"></i>
            <p class="text-muted">Belum ada buku tersedia.</p>
        </div>
    @endif
</section>

<!-- Features -->
<section class="bg-light py-5 mb-4">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Fitur Perpustakaan</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <i class="fas fa-book fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Koleksi Lengkap</h5>
                    <p class="text-muted mb-0">Ribuan buku dari berbagai kategori tersedia untuk Anda.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <i class="fas fa-search fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Pencarian Mudah</h5>
                    <p class="text-muted mb-0">Temukan buku yang Anda cari dengan cepat.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <i class="fas fa-globe fa-3x text-info mb-3"></i>
                    <h5 class="fw-bold">Akses Online</h5>
                    <p class="text-muted mb-0">Baca buku kapan saja dan di mana saja.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="container mb-5">
    <h2 class="fw-bold mb-4">
        <i class="fas fa-tags text-info me-2"></i>Kategori
    </h2>
    @php
        $kategoris = \App\Models\Kategori::withCount('buku')->limit(6)->get();
    @endphp
    <div class="row">
        @forelse($kategoris as $kat)
            <div class="col-md-2 col-6 mb-3">
                <a href="{{ route('koleksi') }}" class="card border-0 shadow-sm text-decoration-none h-100 text-center p-3">
                    <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                    <h6 class="mb-1">{{ $kat->nama }}</h6>
                    <small class="text-muted">{{ $kat->buku_count }} buku</small>
                </a>
            </div>
        @empty
            <div class="col-12 text-center text-muted">
                <p>Belum ada kategori tersedia.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- CTA -->
<section class="bg-primary py-4 text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">Siap Memulai?</h5>
                <p class="mb-0 opacity-75">Bergabunglah dengan kami dan nikmati akses ke koleksi buku lengkap.</p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                @if(session('anggota'))
                    <a href="{{ route('anggota') }}" class="btn btn-light">Halaman Anggota</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-light">Daftar Sekarang</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
