@extends('layouts.admin')

@section('title', 'Kelola Anggota - Admin')
@section('header', 'Kelola Anggota')

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-id-card me-2"></i>Manajemen Anggota</h6>
    <p>Atur data anggota, periksa kelengkapan profil, dan pantau akun terbaru.</p>
</div>

<div class="admin-index-overview mb-4">
    <div class="admin-mini-stat">
        <span>Total Anggota</span>
        <strong>{{ $totalAnggota }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Data Kelas Terisi</span>
        <strong>{{ $denganKelas }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Tanpa Alamat</span>
        <strong>{{ $tanpaAlamat }}</strong>
    </div>
</div>

<div class="admin-form-shell mb-4">
    <form method="GET" action="{{ route('admin.anggota.index') }}" class="row g-3 align-items-end" id="anggotaFilterForm">
        <div class="col-lg-6">
            <label for="q" class="form-label admin-form-label mb-1">Search</label>
            <input
                type="text"
                id="q"
                name="q"
                value="{{ $search }}"
                class="form-control"
                placeholder="Cari NISN, nama, kelas, atau alamat..."
                autocomplete="off"
            >
        </div>
        <div class="col-lg-3">
            <label for="kelas" class="form-label admin-form-label mb-1">Filter Kelas</label>
            <select id="kelas" name="kelas" class="form-select">
                <option value="">Semua Kelas</option>
                @foreach($kelasOptions as $kelasItem)
                    <option value="{{ $kelasItem }}" {{ $kelas === $kelasItem ? 'selected' : '' }}>{{ $kelasItem }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label for="alamat" class="form-label admin-form-label mb-1">Filter Alamat</label>
            <select id="alamat" name="alamat" class="form-select">
                <option value="">Semua</option>
                <option value="lengkap" {{ $alamatStatus === 'lengkap' ? 'selected' : '' }}>Alamat Lengkap</option>
                <option value="kosong" {{ $alamatStatus === 'kosong' ? 'selected' : '' }}>Alamat Kosong</option>
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
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $index => $item)
                        <tr>
                            <td class="text-center">{{ ($anggota->firstItem() ?? 1) + $index }}</td>
                            <td>{{ $item->nisn }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kelas ?? '-' }}</td>
                            <td>{{ Str::limit($item->alamat ?? '-', 30) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.anggota.edit', $item->id) }}" class="btn btn-warning btn-sm admin-icon-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
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
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada data anggota</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $anggota->links() }}
</div>

<script>
    (function () {
        const form = document.getElementById('anggotaFilterForm');
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
