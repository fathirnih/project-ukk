@extends('layouts.app')

@section('title', 'Peminjaman Buku - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold mb-4">
        <i class="fas fa-book-reader text-primary me-2"></i>Peminjaman Buku
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
    @endif

    @if(!session('anggota_id'))
        <div class="alert alert-warning">
            Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk meminjam buku.
        </div>
    @else
        <!-- Form Pinjam Buku -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Ajukan Peminjaman
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('peminjaman.store') }}" method="POST" id="peminjamanForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Buku</label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="bukuTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 50px">Pilih</th>
                                        <th>Judul</th>
                                        <th>Pengarang</th>
                                        <th>Tersedia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bukus as $buku)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="buku-checkbox" data-id="{{ $buku->id }}" data-judul="{{ $buku->judul }}">
                                            </td>
                                            <td>
                                                {{ $buku->judul }}
                                                @if($buku->kategori)
                                                    <span class="badge bg-info">{{ $buku->kategori->nama }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $buku->pengarang }}</td>
                                            <td>{{ $buku->jumlah }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada buku tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row" id="jumlahSection" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Buku</label>
                            <input type="number" class="form-control" name="jumlah[]" min="1" value="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="tanggal_kembali" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>

                    <input type="hidden" name="buku_id" id="bukuIds">

                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="fas fa-paper-plane me-2"></i>Kirim Ajuan
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Peminjaman -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Riwayat Peminjaman
                </h5>
            </div>
            <div class="card-body">
                @if($riwayat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Buku</th>
                                    <th>Status Pinjam</th>
                                    <th>Status Kembali</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $index => $peminjaman)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}<br>
                                            <small class="text-muted">Kembali: {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            @foreach($peminjaman->detailPeminjamans as $detail)
                                                - {{ $detail->buku->judul }} ({{ $detail->jumlah }})<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($peminjaman->status_pinjam == 'pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($peminjaman->status_pinjam == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($peminjaman->status_kembali == 'pending')
                                                <span class="badge bg-secondary">-</span>
                                            @elseif($peminjaman->status_kembali == 'pending_admin')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($peminjaman->status_pinjam == 'disetujui' && $peminjaman->status_kembali == 'pending')
                                                <a href="{{ route('peminjaman.ajuan-kembali', $peminjaman->id) }}" class="btn btn-sm btn-warning" onclick="return confirm('Ajukan pengembalian buku?')">
                                                    <i class="fas fa-undo"></i> Ajukan Kembali
                                                </a>
                                            @elseif($peminjaman->status_pinjam == 'ditolak')
                                                <small class="text-danger">{{ $peminjaman->catatan_penolakan }}</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Belum ada riwayat peminjaman.</p>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.buku-checkbox');
    const submitBtn = document.getElementById('submitBtn');
    const jumlahSection = document.getElementById('jumlahSection');
    const bukuIds = document.getElementById('bukuIds');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const selected = Array.from(checkboxes)
                .filter(c => c.checked)
                .map(c => c.dataset.id);
            
            bukuIds.value = selected.join(',');
            
            if (selected.length > 0) {
                submitBtn.disabled = false;
                jumlahSection.style.display = 'flex';
            } else {
                submitBtn.disabled = true;
                jumlahSection.style.display = 'none';
            }
        });
    });

    document.getElementById('peminjamanForm').addEventListener('submit', function(e) {
        const selected = Array.from(checkboxes)
            .filter(c => c.checked)
            .map(c => c.dataset.id);
        
        if (selected.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu buku!');
        }
    });
});
</script>
@endsection
