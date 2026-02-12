@extends('layouts.app')

@section('title', 'Koleksi Buku - Perpustakaan Digital')

@section('content')
<div class="container">
    <h1 class="mb-4">Koleksi Buku</h1>
    <div class="row">
        @forelse($buku as $item)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->judul }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $item->pengarang }}</h6>
                        <hr>
                        <p class="card-text small">
                            @if($item->isbn)
                                <strong>ISBN:</strong> {{ $item->isbn }}<br>
                            @endif
                            @if($item->penerbit)
                                <strong>Penerbit:</strong> {{ $item->penerbit }}<br>
                            @endif
                            @if($item->tahun_terbit)
                                <strong>Tahun:</strong> {{ $item->tahun_terbit }}<br>
                            @endif
                            @if($item->jumlah)
                                <strong>Jumlah:</strong> {{ $item->jumlah }} buku<br>
                            @endif
                            @if($item->deskripsi)
                                <strong>Deskripsi:</strong> {{ Str::limit($item->deskripsi, 80) }}
                            @endif
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <button class="btn btn-outline-primary btn-sm w-100">Baca Buku</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="p-5 bg-light rounded">
                    <h4 class="mb-3">Belum Ada Buku</h4>
                    <p class="text-muted">Koleksi buku sedang dalam pengembangan.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Home</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
