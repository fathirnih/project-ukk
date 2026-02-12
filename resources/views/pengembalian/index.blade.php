@extends('layouts.member')

@section('title', 'Pengembalian - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold mb-4">
        <i class="fas fa-undo text-primary me-2"></i>Pengembalian Buku
    </h1>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = '/pengembalian';
            }, 1000);
        </script>
    @endif

    @if(!session('anggota_id'))
        <div class="alert alert-warning">
            Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2"></i>Buku yang Sedang Dipinjam
                </h5>
            </div>
            <div class="card-body">
                @if($pinjamans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px">No</th>
                                    <th style="120px">Tgl Pinjam</th>
                                    <th style="120px">Tgl Kembali</th>
                                    <th>Buku</th>
                                    <th style="130px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pinjamans as $index => $peminjaman)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar-plus me-1 text-primary"></i>
                                                {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar-check me-1 text-success"></i>
                                                {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @foreach($peminjaman->detailPeminjamans as $detail)
                                                <div class="d-flex align-items-center mb-2">
                                                    @if($detail->buku->cover && file_exists(public_path('storage/covers/' . $detail->buku->cover)))
                                                        <img src="{{ asset('storage/covers/' . $detail->buku->cover) }}" 
                                                             alt="{{ $detail->buku->judul }}" 
                                                             class="img-thumbnail me-2"
                                                             style="width: 35px; height: 47px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary text-white me-2 d-flex align-items-center justify-content-center" 
                                                             style="width: 35px; height: 47px; font-size: 14px;">
                                                            <i class="fas fa-book"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <small><strong>{{ $detail->buku->judul }}</strong></small>
                                                        <br><small class="text-primary">Qty: {{ $detail->jumlah }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('peminjaman.ajuan-kembali', $peminjaman->id) }}" 
                                               class="btn btn-sm btn-primary w-100"
                                               onclick="return confirm('Ajukan pengembalian buku ini?')">
                                                <i class="fas fa-undo me-1"></i>Ajukan
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5>Tidak ada buku yang dipinjam</h5>
                        <p class="text-muted">Anda tidak memiliki buku yang sedang dipinjam.</p>
                        <a href="/peminjaman" class="btn btn-primary">
                            <i class="fas fa-book-reader me-2"></i>Ajuan Peminjaman
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
