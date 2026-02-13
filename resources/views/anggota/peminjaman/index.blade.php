@extends('layouts.member')

@section('title', 'Peminjaman Buku - Perpustakaan Digital')

@section('content')
<section class="member-page member-main-block">
    <div class="member-page-header">
        <h1><i class="fas fa-book-reader me-2"></i>Peminjaman Buku</h1>
        <p>Ajukan peminjaman buku dari koleksi yang tersedia.</p>
    </div>

    @if(!session('anggota_id'))
        <div class="alert alert-warning">
            Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk meminjam buku.
        </div>
    @else
        <!-- Form Pinjam Buku -->
        <div class="card member-section-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Ajukan Peminjaman
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('peminjaman.index') }}" id="memberBookFilterForm" class="mb-4 member-book-filter-form">
                    <div class="row g-2 align-items-center member-book-filter-row">
                        <div class="col-md-3">
                            <select name="kategori_id" id="memberFilterKategori" class="form-select form-select-sm member-book-filter-input">
                                <option value="">Semua kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ (string) $kategoriId === (string) $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="pengarang" id="memberFilterPengarang" class="form-select form-select-sm member-book-filter-input">
                                <option value="">Semua pengarang</option>
                                @foreach($pengarangList as $itemPengarang)
                                    <option value="{{ $itemPengarang }}" {{ $pengarang === $itemPengarang ? 'selected' : '' }}>
                                        {{ $itemPengarang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="penerbit" id="memberFilterPenerbit" class="form-select form-select-sm member-book-filter-input">
                                <option value="">Semua penerbit</option>
                                @foreach($penerbitList as $itemPenerbit)
                                    <option value="{{ $itemPenerbit }}" {{ $penerbit === $itemPenerbit ? 'selected' : '' }}>
                                        {{ $itemPenerbit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 ms-md-auto member-book-filter-search">
                            <input
                                type="text"
                                name="q"
                                id="memberFilterSearch"
                                class="form-control form-control-sm member-book-filter-input"
                                placeholder="Cari judul, ISBN, pengarang, penerbit..."
                                value="{{ $search }}"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </form>

                <form action="{{ route('peminjaman.store') }}" method="POST" id="peminjamanForm" data-anggota-id="{{ $anggota->id }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Buku</label>
                        <div class="member-book-picker">
                            @forelse($bukus as $buku)
                                <div class="member-select-card {{ $buku->jumlah <= 0 ? 'opacity-75' : '' }}" data-book-item>
                                    <div class="member-select-top">
                                        <div>
                                            @if($buku->jumlah > 0)
                                                <input type="checkbox" class="form-check-input buku-checkbox" data-id="{{ $buku->id }}" data-judul="{{ $buku->judul }}">
                                            @else
                                                <span class="text-muted"><i class="fas fa-times"></i></span>
                                            @endif
                                        </div>
                                        <div>
                                            @if($buku->cover && file_exists(public_path('storage/covers/' . $buku->cover)))
                                                <img src="{{ asset('storage/covers/' . $buku->cover) }}" alt="{{ $buku->judul }}" class="member-book-cover">
                                            @else
                                                <div class="member-book-fallback bg-secondary text-white d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="member-select-main">
                                            <div class="fw-bold">{{ $buku->judul }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $buku->pengarang }}
                                                @if($buku->penerbit)
                                                    | {{ $buku->penerbit }}
                                                @endif
                                            </small>
                                            <div class="mt-1 d-flex gap-2 flex-wrap">
                                                @if($buku->kategori)
                                                    <span class="badge text-bg-info">{{ $buku->kategori->nama }}</span>
                                                @endif
                                                @if($buku->jumlah > 0)
                                                    <span class="badge text-bg-success">{{ $buku->jumlah }} tersedia</span>
                                                @else
                                                    <span class="badge text-bg-danger">Stok habis</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="member-qty-wrap">
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
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="member-empty-state">
                                    <i class="fas fa-book"></i>
                                    <h5>Tidak ada buku tersedia</h5>
                                </div>
                            @endforelse
                        </div>
                        @if(method_exists($bukus, 'hasPages') && $bukus->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $bukus->links() }}
                            </div>
                        @endif
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

                    <button type="submit" class="btn btn-primary member-action-button" id="submitBtn" disabled>
                        <i class="fas fa-paper-plane me-2"></i>Kirim Ajuan
                    </button>
                </form>
            </div>
        </div>
    @endif
</section>
@endsection
