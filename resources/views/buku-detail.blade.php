@extends($app_layout ?? 'layouts.app')

@section('title', $buku->judul . ' - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('koleksi') }}">Koleksi</a></li>
            <li class="breadcrumb-item active">{{ $buku->judul }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-4">
                @if($buku->cover && file_exists(public_path('covers/' . $buku->cover)))
                    <img src="{{ asset('covers/' . $buku->cover) }}" class="img-fluid rounded-start w-100" alt="{{ $buku->judul }}" style="object-fit: cover; min-height: 400px;">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center text-white h-100 rounded-start" style="min-height: 400px;">
                        <div class="text-center">
                            <i class="fas fa-book fa-5x"></i>
                            <p class="mt-2">Tidak ada cover</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h2 class="card-title fw-bold mb-2">{{ $buku->judul }}</h2>
                    <h5 class="card-subtitle mb-3 text-muted">
                        <i class="fas fa-user-edit me-1"></i>{{ $buku->pengarang }}
                    </h5>
                    @if($buku->kategori)
                        <span class="badge bg-info mb-3">
                            <i class="fas fa-tag me-1"></i>{{ $buku->kategori->nama }}
                        </span>
                    @endif
                    
                    @if($buku->deskripsi)
                        <div class="mb-4">
                            <h6 class="fw-bold">Deskripsi</h6>
                            <p class="card-text">{{ $buku->deskripsi }}</p>
                        </div>
                    @endif

                    <hr>

                    <h6 class="fw-bold mb-3">Detail Buku</h6>
                    <table class="table table-bordered">
                        <tr>
                            <td width="150" class="fw-bold bg-light">ISBN</td>
                            <td>{{ $buku->isbn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Judul</td>
                            <td>{{ $buku->judul }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Pengarang</td>
                            <td>{{ $buku->pengarang }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Penerbit</td>
                            <td>{{ $buku->penerbit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Tahun Terbit</td>
                            <td>{{ $buku->tahun_terbit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Jumlah</td>
                            <td>{{ $buku->jumlah ?? 0 }} buku</td>
                        </tr>
                        @if($buku->kategori)
                        <tr>
                            <td class="fw-bold bg-light">Kategori</td>
                            <td>{{ $buku->kategori->nama }}</td>
                        </tr>
                        @endif
                    </table>

                    <div class="mt-4">
                        @if($buku->jumlah > 0)
                            <button class="btn btn-primary btn-lg me-2">
                                <i class="fas fa-book-open me-2"></i>Baca Buku
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg me-2" disabled>
                                <i class="fas fa-times me-2"></i>Tidak Tersedia
                            </button>
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
