@extends($app_layout ?? 'layouts.app')

@section('title', $buku->judul . ' - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb detail-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('koleksi') }}">Koleksi</a></li>
            <li class="breadcrumb-item active">{{ $buku->judul }}</li>
        </ol>
    </nav>

    <div class="detail-hero mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">{{ $buku->judul }}</h1>
                <p class="mb-0 opacity-75"><i class="fas fa-user-edit me-1"></i>{{ $buku->pengarang }}</p>
            </div>
            <div class="col-lg-4">
                <div class="detail-hero-stats">
                    <div><span>Stok</span><strong>{{ $buku->jumlah ?? 0 }}</strong></div>
                    <div><span>Kategori</span><strong>{{ $buku->kategori?->nama ?? 'Umum' }}</strong></div>
                    <div><span>Tahun</span><strong>{{ $buku->tahun_terbit ?? '-' }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 buku-detail-card">
        <div class="row g-0">
            <div class="col-md-4 detail-cover-wrap">
                @if($buku->cover && file_exists(public_path('covers/' . $buku->cover)))
                    <img src="{{ asset('covers/' . $buku->cover) }}" class="img-fluid w-100 detail-cover-img" alt="{{ $buku->judul }}">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center text-white h-100 detail-cover-img">
                        <div class="text-center">
                            <i class="fas fa-book fa-5x"></i>
                            <p class="mt-2">Tidak ada cover</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($buku->kategori)
                        <span class="badge text-bg-info">
                            <i class="fas fa-tag me-1"></i>{{ $buku->kategori->nama }}
                        </span>
                        @endif
                        @if(($buku->jumlah ?? 0) > 0)
                            <span class="badge text-bg-success">{{ $buku->jumlah }} tersedia</span>
                        @else
                            <span class="badge text-bg-danger">Tidak tersedia</span>
                        @endif
                        @if($buku->isbn)
                            <span class="badge text-bg-secondary"><i class="fas fa-barcode me-1"></i>{{ $buku->isbn }}</span>
                        @endif
                    </div>
                    
                    @if($buku->deskripsi)
                        <div class="detail-block mb-4">
                            <h6 class="fw-bold mb-2">Deskripsi</h6>
                            <p class="card-text">{{ $buku->deskripsi }}</p>
                        </div>
                    @endif

                    <div class="detail-meta-grid mb-4">
                        <div class="detail-meta-item"><span>ISBN</span><strong>{{ $buku->isbn ?? '-' }}</strong></div>
                        <div class="detail-meta-item"><span>Penerbit</span><strong>{{ $buku->penerbit ?? '-' }}</strong></div>
                        <div class="detail-meta-item"><span>Tahun Terbit</span><strong>{{ $buku->tahun_terbit ?? '-' }}</strong></div>
                        <div class="detail-meta-item"><span>Jumlah</span><strong>{{ $buku->jumlah ?? 0 }} buku</strong></div>
                    </div>

                    <div class="mt-4 d-flex flex-wrap gap-2">
                        @if(session('anggota_id') && $buku->jumlah > 0)
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-book-reader me-2"></i>Ajukan Peminjaman
                            </a>
                        @elseif($buku->jumlah <= 0)
                            <button class="btn btn-secondary btn-lg" disabled>
                                <i class="fas fa-times me-2"></i>Stok Habis
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login untuk Meminjam
                            </a>
                        @endif
                        <a href="{{ route('koleksi') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
