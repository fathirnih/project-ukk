@extends($app_layout ?? 'layouts.app')

@section('title', 'Koleksi Buku - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <div class="collection-hero mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="fw-bold mb-2">
                    <i class="fas fa-book-open me-2"></i>Koleksi Buku
                </h1>
                <p class="mb-0 opacity-75">Temukan buku yang Anda cari dari koleksi kami yang terus diperbarui.</p>
                <div class="collection-hero-tags mt-3">
                    <span><i class="fas fa-circle-check me-1"></i>Kurasi Berkala</span>
                    <span><i class="fas fa-compass me-1"></i>Navigasi Mudah</span>
                    <span><i class="fas fa-box-archive me-1"></i>Stok Realtime</span>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="collection-hero-stat">
                            <span>Total Ditampilkan</span>
                            <strong>{{ $totalDitampilkan }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="collection-hero-stat">
                            <span>Kategori Aktif</span>
                            <strong>{{ $kategoriAktif }}</strong>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="collection-hero-stat">
                            <span>Buku Siap Dipinjam</span>
                            <strong>{{ $stokTersedia }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="collection-filter-card mb-4">
        <div class="collection-filter-head d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h6 class="mb-1"><i class="fas fa-sliders me-2"></i>Pencarian Cerdas</h6>
                <p class="mb-0">Ketik atau pilih filter, hasil akan otomatis diperbarui.</p>
            </div>
            <span class="collection-filter-chip">Realtime</span>
        </div>
        <div class="collection-filter-body">
            <form method="GET" action="{{ route('koleksi') }}" id="koleksiFilterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label for="q" class="form-label mb-1">Cari Buku</label>
                        <div class="collection-input-wrap">
                            <i class="fas fa-magnifying-glass"></i>
                            <input
                                id="q"
                                type="text"
                                name="q"
                                class="form-control collection-search-input"
                                placeholder="Judul, ISBN, kategori, pengarang, penerbit, deskripsi..."
                                value="{{ $search }}"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label for="kategori_id" class="form-label mb-1">Kategori</label>
                        <select id="kategori_id" name="kategori_id" class="form-select collection-filter-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriOptions as $kategori)
                                <option value="{{ $kategori->id }}" {{ (string) $kategoriId === (string) $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label for="pengarang" class="form-label mb-1">Pengarang</label>
                        <select id="pengarang" name="pengarang" class="form-select collection-filter-select">
                            <option value="">Semua Pengarang</option>
                            @foreach($pengarangOptions as $pengarangItem)
                                <option value="{{ $pengarangItem }}" {{ $pengarang === $pengarangItem ? 'selected' : '' }}>
                                    {{ $pengarangItem }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label for="penerbit" class="form-label mb-1">Penerbit</label>
                        <select id="penerbit" name="penerbit" class="form-select collection-filter-select">
                            <option value="">Semua Penerbit</option>
                            @foreach($penerbitOptions as $penerbitItem)
                                <option value="{{ $penerbitItem }}" {{ $penerbit === $penerbitItem ? 'selected' : '' }}>
                                    {{ $penerbitItem }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($buku->count() > 0)
        <div class="row g-4">
            @foreach($buku as $item)
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 collection-book-card">
                        @if($item->cover && file_exists(public_path('covers/' . $item->cover)))
                            <img src="{{ asset('covers/' . $item->cover) }}" class="card-img-top collection-book-cover" alt="{{ $item->judul }}">
                        @else
                            <div class="collection-book-cover bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="fas fa-book fa-4x"></i>
                            </div>
                        @endif
                        <div class="card-body pb-2">
                            <h6 class="card-title fw-bold text-truncate" title="{{ $item->judul }}">{{ $item->judul }}</h6>
                            <p class="card-text small text-muted mb-2">{{ $item->pengarang }}</p>
                            <p class="small text-muted mb-2"><i class="fas fa-building me-1"></i>{{ $item->penerbit ?? 'Penerbit belum diisi' }}</p>
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                @if($item->kategori)
                                    <span class="badge text-bg-info">{{ $item->kategori->nama }}</span>
                                @else
                                    <span class="badge text-bg-secondary">Umum</span>
                                @endif
                                @if(($item->jumlah ?? 0) > 0)
                                    <span class="badge text-bg-success">{{ $item->jumlah }} tersedia</span>
                                @else
                                    <span class="badge text-bg-danger">Kosong</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="{{ route('buku.detail', $item->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $buku->links() }}
        </div>
    @else
        <div class="text-center py-5 bg-light rounded-4 collection-empty">
            <i class="fas fa-book fa-5x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">Buku Tidak Ditemukan</h4>
            <p class="text-muted mb-4">Coba ubah kata kunci atau filter yang Anda gunakan.</p>
            <a href="{{ route('koleksi') }}" class="btn btn-primary">
                <i class="fas fa-rotate-left me-2"></i>Tampilkan Semua Buku
            </a>
        </div>
    @endif
</div>

<script>
    (function () {
        const form = document.getElementById('koleksiFilterForm');
        if (!form) return;

        const searchInput = form.querySelector('#q');
        const selects = form.querySelectorAll('select');
        let searchTimer = null;

        const submitForm = function () {
            if (searchTimer) {
                clearTimeout(searchTimer);
                searchTimer = null;
            }
            form.submit();
        };

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                if (searchTimer) {
                    clearTimeout(searchTimer);
                }

                searchTimer = setTimeout(function () {
                    submitForm();
                }, 400);
            });
        }

        selects.forEach(function (selectEl) {
            selectEl.addEventListener('change', submitForm);
        });

        document.querySelectorAll('.pagination a').forEach(function (paginationLink) {
            paginationLink.addEventListener('click', function () {
                if (searchTimer) {
                    clearTimeout(searchTimer);
                    searchTimer = null;
                }
            });
        });
    })();
</script>
@endsection
