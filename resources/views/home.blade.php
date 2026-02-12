@extends('layouts.app')

@section('title', 'Home - Perpustakaan Digital')

@section('content')
<div class="container">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Selamat Datang di Perpustakaan Digital</h1>
            <p class="col-md-8 fs-4">Temukan koleksi buku terbaik dari berbagai genre dan penulis terkenal. Jelajahi dunia pengetahuan tanpa batas.</p>
            <a href="{{ route('koleksi') }}" class="btn btn-primary btn-lg">Jelajahi Koleksi</a>
        </div>
    </div>

    <h2 class="mb-4">Buku Unggulan</h2>
    <div class="row">
        @forelse($featuredBuku as $buku)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $buku->judul }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $buku->pengarang }}</h6>
                        <p class="card-text small">{{ Str::limit($buku->deskripsi, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <small class="text-muted">Penerbit: {{ $buku->penerbit ?? '-' }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">Belum ada buku tersedia. <a href="{{ route('koleksi') }}">Lihat koleksi</a></p>
            </div>
        @endforelse
    </div>
</div>
@endsection
