@extends($app_layout ?? 'layouts.app')

@section('title', 'Home - Perpustakaan Digital')

@section('content')
@php
    $kategoris = \App\Models\Kategori::withCount('buku')->limit(6)->get();
@endphp

<section class="home-hero py-5 mb-5">
    <div class="container">
        <div class="home-hero-panel">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="home-pill mb-3 d-inline-flex">Literasi Digital Sekolah</span>
                    <h1 class="display-5 fw-bold mb-3">Baca, Pinjam, dan Tumbuh Bersama</h1>
                    <p class="lead mb-4">Portal perpustakaan modern untuk menemukan buku terbaik, mengelola peminjaman, dan memperluas wawasan setiap hari.</p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('koleksi') }}" class="btn btn-light btn-lg fw-bold">
                            <i class="fas fa-compass me-2"></i>Mulai Jelajah
                        </a>
                        @if(session('anggota_id'))
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-book-reader me-2"></i>Pinjam Sekarang
                            </a>
                        @endif
                    </div>
                    <div class="home-hero-badges">
                        <span><i class="fas fa-circle-check me-1"></i>Koleksi Berkualitas</span>
                        <span><i class="fas fa-bolt me-1"></i>Proses Cepat</span>
                        <span><i class="fas fa-shield-heart me-1"></i>Ramah Siswa</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="home-hero-stack">
                        <div class="home-stack-card">
                            <small>Kategori Aktif</small>
                            <strong>{{ $kategoris->count() }}</strong>
                        </div>
                        <div class="home-stack-card">
                            <small>Buku Unggulan</small>
                            <strong>{{ $featuredBuku->count() }}</strong>
                        </div>
                        <div class="home-stack-card">
                            <small>Status Platform</small>
                            <strong>Online</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-fire text-danger me-2"></i>Pilihan Minggu Ini</h2>
        <a href="{{ route('koleksi') }}" class="btn btn-outline-primary">Lihat Semua</a>
    </div>
    @if($featuredBuku->count() > 0)
        <div class="row g-4">
            @foreach($featuredBuku as $buku)
                <div class="col-md-6 col-xl-3">
                    <article class="card h-100 border-0 home-book-card">
                        @if($buku->cover && file_exists(public_path('covers/' . $buku->cover)))
                            <img src="{{ asset('covers/' . $buku->cover) }}" class="card-img-top home-book-cover" alt="{{ $buku->judul }}">
                        @else
                            <div class="home-book-cover bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="fas fa-book fa-4x"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            @if($buku->kategori)
                                <span class="badge text-bg-info mb-2">{{ $buku->kategori->nama }}</span>
                            @endif
                            <h5 class="card-title fw-bold mb-1">{{ $buku->judul }}</h5>
                            <p class="text-muted small mb-2"><i class="fas fa-feather-pointed me-1"></i>{{ $buku->pengarang }}</p>
                            <p class="card-text small text-muted">{{ Str::limit($buku->deskripsi, 68) }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="{{ route('buku.detail', $buku->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-light rounded-4">
            <i class="fas fa-book fa-4x text-muted mb-3"></i>
            <p class="text-muted">Belum ada buku tersedia.</p>
        </div>
    @endif
</section>

<section class="home-flow py-5 mb-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">Alur Penggunaan</h2>
            <p class="text-muted mb-0">Dari menemukan buku hingga pengembalian, semua bisa dilakukan dalam beberapa langkah.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="home-flow-card">
                    <span class="home-flow-num">01</span>
                    <h5 class="fw-bold">Pilih Buku</h5>
                    <p class="text-muted mb-0">Jelajahi koleksi berdasarkan kategori dan penulis favorit.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="home-flow-card">
                    <span class="home-flow-num">02</span>
                    <h5 class="fw-bold">Ajukan Pinjam</h5>
                    <p class="text-muted mb-0">Ajukan peminjaman langsung dari dashboard anggota dengan cepat.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="home-flow-card">
                    <span class="home-flow-num">03</span>
                    <h5 class="fw-bold">Kembalikan Tepat Waktu</h5>
                    <p class="text-muted mb-0">Pantau riwayat peminjaman dan lakukan pengembalian secara teratur.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-tags text-info me-2"></i>Kategori Populer</h2>
        <a href="{{ route('koleksi') }}" class="btn btn-outline-primary btn-sm">Eksplorasi</a>
    </div>
    <div class="row g-3">
        @forelse($kategoris as $kat)
            <div class="col-md-4 col-xl-2 col-6">
                <a href="{{ route('koleksi') }}" class="card border-0 text-decoration-none h-100 text-center p-3 home-category-card">
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

<section class="home-cta py-4 text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">Siap Memulai Perjalanan Membaca?</h5>
                <p class="mb-0 opacity-75">Akses koleksi digital dan kelola peminjaman dengan pengalaman yang lebih nyaman.</p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                @if(session('anggota_id'))
                    <a href="{{ route('anggota') }}" class="btn btn-light">Halaman Anggota</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-light">Daftar Sekarang</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
