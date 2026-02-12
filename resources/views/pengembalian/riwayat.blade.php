@extends('layouts.member')

@section('title', 'Riwayat Pengembalian - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold mb-4">
        <i class="fas fa-undo text-primary me-2"></i>Riwayat Pengembalian
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
                window.location.href = '/pengembalian/riwayat?t=' + new Date().getTime();
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
                    <i class="fas fa-list me-2"></i>Daftar Riwayat Pengembalian
                </h5>
            </div>
            <div class="card-body">
                @if($riwayat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px">No</th>
                                    <th style="120px">Tgl Pengajuan</th>
                                    <th>Buku</th>
                                    <th style="110px">Status</th>
                                    <th style="130px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $index => $pengembalian)
                                    <tr>
                                        <td class="text-center">{{ $riwayat->firstItem() + $index }}</td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar-plus me-1 text-primary"></i>
                                                {{ $pengembalian->tanggal_pengajuan->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @foreach($pengembalian->peminjaman->detailPeminjamans as $detail)
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
                                            @if($pengembalian->status == 'pending_admin')
                                                <span class="badge bg-info">Menunggu</span>
                                            @elseif($pengembalian->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pengembalian->status == 'pending_admin')
                                                <span class="text-muted small">Menunggu...</span>
                                            @elseif($pengembalian->status == 'ditolak')
                                                <a href="{{ route('pengembalian.ajukan-ulang', $pengembalian->id) }}" 
                                                   class="btn btn-sm btn-primary w-100"
                                                   onclick="return confirm('Ajukan pengembalian ulang?')">
                                                    <i class="fas fa-redo me-1"></i>Ajukan Lagi
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $riwayat->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-undo fa-4x text-muted mb-3"></i>
                        <h5>Belum ada riwayat pengembalian</h5>
                        <p class="text-muted">Riwayat pengembalian akan muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
