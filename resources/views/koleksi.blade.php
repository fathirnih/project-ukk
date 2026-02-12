@extends($app_layout ?? 'layouts.app')

@section('title', 'Koleksi Buku - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="text-center mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-book-open text-primary me-2"></i>Koleksi Buku
        </h1>
        <p class="text-muted">Temukan buku yang Anda cari dari koleksi kami</p>
    </div>

    @if($buku->count() > 0)
        <div class="row">
            @foreach($buku as $item)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($item->cover && file_exists(public_path('covers/' . $item->cover)))
                            <img src="{{ asset('covers/' . $item->cover) }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center text-white" style="height: 200px;">
                                <i class="fas fa-book fa-4x"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-truncate" title="{{ $item->judul }}">{{ $item->judul }}</h6>
                            <p class="card-text small text-muted mb-1">{{ $item->pengarang }}</p>
                            @if($item->kategori)
                                <span class="badge bg-info">{{ $item->kategori->nama }}</span>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('buku.detail', $item->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-light rounded">
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
