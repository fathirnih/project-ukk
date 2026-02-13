@extends('layouts.admin')

@section('title', 'Kelola Buku - Admin')
@section('header', 'Kelola Buku')

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-layer-group me-2"></i>Manajemen Koleksi Buku</h6>
    <p>Kelola data buku, cek stok menipis, dan pastikan koleksi memiliki cover yang layak tampil.</p>
</div>

<div class="admin-index-overview mb-4">
    <div class="admin-mini-stat">
        <span>Total Buku</span>
        <strong>{{ $totalBuku }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Stok Menipis</span>
        <strong>{{ $stokMenipis }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Tanpa Cover</span>
        <strong>{{ $tanpaCover }}</strong>
    </div>
</div>

<div class="admin-form-shell mb-4">
    <form method="GET" action="{{ route('admin.buku.index') }}" class="row g-3 align-items-end" id="bukuFilterForm">
        <div class="col-lg-4">
            <label for="q" class="form-label admin-form-label mb-1">Search</label>
            <input
                type="text"
                id="q"
                name="q"
                value="{{ $search }}"
                class="form-control"
                placeholder="Cari judul, ISBN, pengarang, penerbit..."
                autocomplete="off"
            >
        </div>
        <div class="col-lg-2">
            <label for="kategori_id" class="form-label admin-form-label mb-1">Kategori</label>
            <select id="kategori_id" name="kategori_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoriOptions as $kategori)
                    <option value="{{ $kategori->id }}" {{ (string) $kategoriId === (string) $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label for="pengarang" class="form-label admin-form-label mb-1">Pengarang</label>
            <select id="pengarang" name="pengarang" class="form-select">
                <option value="">Semua</option>
                @foreach($pengarangOptions as $pengarangItem)
                    <option value="{{ $pengarangItem }}" {{ $pengarang === $pengarangItem ? 'selected' : '' }}>{{ $pengarangItem }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label for="penerbit" class="form-label admin-form-label mb-1">Penerbit</label>
            <select id="penerbit" name="penerbit" class="form-select">
                <option value="">Semua</option>
                @foreach($penerbitOptions as $penerbitItem)
                    <option value="{{ $penerbitItem }}" {{ $penerbit === $penerbitItem ? 'selected' : '' }}>{{ $penerbitItem }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-1">
            <label for="stok" class="form-label admin-form-label mb-1">Stok</label>
            <select id="stok" name="stok" class="form-select">
                <option value="">Semua</option>
                <option value="tersedia" {{ $stok === 'tersedia' ? 'selected' : '' }}>Ada</option>
                <option value="menipis" {{ $stok === 'menipis' ? 'selected' : '' }}>Menipis</option>
                <option value="habis" {{ $stok === 'habis' ? 'selected' : '' }}>Habis</option>
            </select>
        </div>
    </form>
</div>

<div class="card admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 admin-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Cover</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th>Penerbit</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buku as $index => $item)
                        <tr>
                            <td class="text-center">{{ ($buku->firstItem() ?? 1) + $index }}</td>
                            <td>
                                @if($item->cover && file_exists(public_path('covers/' . $item->cover)))
                                    <img src="{{ asset('covers/' . $item->cover) }}" alt="{{ $item->judul }}" style="width: 50px; height: 70px; object-fit: cover;" class="rounded">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 70px;">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->judul }}</td>
                            <td>{{ $item->pengarang }}</td>
                            <td>
                                @if($item->kategori)
                                    <span class="badge bg-info">{{ $item->kategori->nama }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->penerbit ?? '-' }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.buku.edit', $item->id) }}" class="btn btn-warning btn-sm admin-icon-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.buku.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm admin-icon-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada data buku</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $buku->links() }}
</div>

<script>
    (function () {
        const form = document.getElementById('bukuFilterForm');
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
    })();
</script>
@endsection
