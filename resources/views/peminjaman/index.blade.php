@extends('layouts.member')

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
        <div class="card shadow-sm">
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
                                        <th style="width: 80px">Cover</th>
                                        <th>Buku</th>
                                        <th style="width: 100px">Stok</th>
                                        <th style="width: 120px">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bukus as $buku)
                                        <tr class="{{ $buku->jumlah <= 0 ? 'table-secondary' : '' }}">
                                            <td class="text-center align-middle">
                                                @if($buku->jumlah > 0)
                                                    <input type="checkbox" class="buku-checkbox" data-id="{{ $buku->id }}" data-judul="{{ $buku->judul }}">
                                                @else
                                                    <span class="text-muted"><i class="fas fa-times"></i></span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($buku->cover && file_exists(public_path('storage/covers/' . $buku->cover)))
                                                    <img src="{{ asset('storage/covers/' . $buku->cover) }}" 
                                                         alt="{{ $buku->judul }}" 
                                                         class="img-thumbnail"
                                                         style="width: 60px; height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 80px; font-size: 24px;">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <strong>{{ $buku->judul }}</strong>
                                                @if($buku->kategori)
                                                    <span class="badge bg-info">{{ $buku->kategori->nama }}</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $buku->pengarang }}
                                                    @if($buku->penerbit)
                                                        | {{ $buku->penerbit }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($buku->jumlah > 0)
                                                    <span class="badge bg-success">{{ $buku->jumlah }}</span>
                                                @else
                                                    <span class="badge bg-danger">Habis</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if($buku->jumlah > 0)
                                                    <input type="number" 
                                                           class="form-control form-control-sm jumlah-input" 
                                                           name="jumlah[{{ $buku->id }}]" 
                                                           min="1" 
                                                           max="{{ $buku->jumlah }}" 
                                                           value="1" 
                                                           disabled
                                                           data-max="{{ $buku->jumlah }}">
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada buku tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="tanggal_kembali" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="buku_ids" id="bukuIds">

                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="fas fa-paper-plane me-2"></i>Kirim Ajuan
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.buku-checkbox');
    const submitBtn = document.getElementById('submitBtn');
    const bukuIds = document.getElementById('bukuIds');
    
    function updateFormState() {
        const selectedIds = [];
        
        checkboxes.forEach(function(checkbox) {
            const row = checkbox.closest('tr');
            const jumlahInput = row.querySelector('.jumlah-input');
            
            if (checkbox.checked) {
                selectedIds.push(checkbox.dataset.id);
                jumlahInput.disabled = false;
                jumlahInput.closest('td').classList.add('table-primary');
            } else {
                jumlahInput.disabled = true;
                jumlahInput.value = 1;
                jumlahInput.closest('td').classList.remove('table-primary');
            }
        });
        
        bukuIds.value = JSON.stringify(selectedIds);
        
        if (selectedIds.length > 0) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateFormState);
    });
    
    document.querySelectorAll('.jumlah-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const max = parseInt(this.dataset.max);
            const value = parseInt(this.value);
            
            if (value < 1) this.value = 1;
            if (value > max) this.value = max;
            
            updateFormState();
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
