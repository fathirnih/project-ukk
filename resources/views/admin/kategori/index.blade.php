@extends('layouts.admin')

@section('title', 'Kelola Kategori - Admin')
@section('header', 'Kelola Kategori')

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-sitemap me-2"></i>Struktur Kategori</h6>
    <p>Gunakan kategori untuk menjaga koleksi tetap rapi dan memudahkan pencarian buku.</p>
</div>

<div class="admin-index-overview mb-4">
    <div class="admin-mini-stat">
        <span>Total Kategori</span>
        <strong>{{ $totalKategori }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Buku Terkategori</span>
        <strong>{{ $totalBukuTerkategori }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Kategori Kosong</span>
        <strong>{{ $kategoriKosong }}</strong>
    </div>
</div>

<div class="admin-form-shell mb-4">
    <form method="GET" action="{{ route('admin.kategori.index') }}" class="row g-3 align-items-end" id="kategoriFilterForm">
        <div class="col-lg-8">
            <label for="q" class="form-label admin-form-label mb-1">Search</label>
            <input
                type="text"
                id="q"
                name="q"
                value="{{ $search }}"
                class="form-control"
                placeholder="Cari nama kategori atau keterangan..."
                autocomplete="off"
            >
        </div>
        <div class="col-lg-3">
            <label for="status" class="form-label admin-form-label mb-1">Filter Status</label>
            <select id="status" name="status" class="form-select">
                <option value="">Semua</option>
                <option value="terpakai" {{ $status === 'terpakai' ? 'selected' : '' }}>Terpakai</option>
                <option value="kosong" {{ $status === 'kosong' ? 'selected' : '' }}>Kosong</option>
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
                        <th>Nama Kategori</th>
                        <th>Keterangan</th>
                        <th class="text-center">Jumlah Buku</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $index => $item)
                        <tr>
                            <td class="text-center">{{ ($kategori->firstItem() ?? 1) + $index }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ Str::limit($item->keterangan ?? '-', 50) }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $item->buku_count }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.kategori.edit', $item->id) }}" class="btn btn-warning btn-sm admin-icon-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada data kategori</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $kategori->links() }}
</div>

<script>
    (function () {
        const form = document.getElementById('kategoriFilterForm');
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
