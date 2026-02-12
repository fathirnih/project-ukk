@extends('layouts.app')

@section('title', 'Tentang - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-info-circle text-primary me-2"></i>Tentang Kami
        </h1>
        <p class="text-muted">Pelajari lebih lanjut tentang Perpustakaan Digital</p>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-center mb-4">
                        <i class="fas fa-book-open text-primary me-2"></i>Perpustakaan Digital
                    </h3>
                    <p class="text-center text-muted mb-4">
                        Platform online yang menyediakan akses mudah dan cepat ke berbagai koleksi buku dari seluruh dunia.
                    </p>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5 class="fw-bold">
                                <i class="fas fa-eye text-primary me-2"></i>Visi
                            </h5>
                            <p class="text-muted mb-0">
                                Menjadi perpustakaan digital terdepan yang memberdayakan masyarakat melalui akses informasi yang mudah dan terjangkau.
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="fw-bold">
                                <i class="fas fa-bullseye text-success me-2"></i>Misi
                            </h5>
                            <ul class="text-muted mb-0 ps-4">
                                <li>Menyediakan koleksi buku yang lengkap dan berkualitas</li>
                                <li>Mempermudah akses informasi bagi masyarakat</li>
                                <li>Mendorong gemar membaca di kalangan generasi muda</li>
                                <li>Menggunakan teknologi terkini untuk pengalaman membaca yang optimal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <i class="fas fa-book fa-3x text-primary mb-3"></i>
                <h2 class="fw-bold">{{ \App\Models\Buku::count() }}</h2>
                <p class="text-muted mb-0">Total Buku</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <i class="fas fa-users fa-3x text-success mb-3"></i>
                <h2 class="fw-bold">{{ \App\Models\Anggota::count() }}</h2>
                <p class="text-muted mb-0">Total Anggota</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <i class="fas fa-tags fa-3x text-info mb-3"></i>
                <h2 class="fw-bold">{{ \App\Models\Kategori::count() }}</h2>
                <p class="text-muted mb-0">Total Kategori</p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="card border-0 shadow-sm bg-primary text-white">
        <div class="card-body p-4 text-center">
            <h5 class="mb-3">Siap Memulai?</h5>
            <p class="mb-3">Bergabunglah dengan ribuan pembaca lainnya dan nikmati akses ke koleksi buku lengkap kami.</p>
            @if(session('anggota'))
                <a href="{{ route('anggota') }}" class="btn btn-light">Halaman Anggota</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light">Daftar Sekarang</a>
            @endif
        </div>
    </div>
</div>
@endsection
