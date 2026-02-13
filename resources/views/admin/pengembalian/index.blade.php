@extends('layouts.admin')

@section('title', 'Konfirmasi Pengembalian')

@section('header')
<i class="fas fa-undo me-2"></i>Konfirmasi Pengembalian
@endsection

@section('content')
<div class="admin-page-intro py-2 px-3">
    <h6 class="mb-1"><i class="fas fa-clipboard-check me-2"></i>Workflow Pengembalian</h6>
    <p class="mb-0 small">Validasi pengembalian buku dan update stok.</p>
</div>

<div class="admin-index-overview mb-3">
    <div class="admin-mini-stat">
        <span>Menunggu</span>
        <strong>{{ $statPending }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Ditolak</span>
        <strong>{{ $statDitolak }}</strong>
    </div>
    <div class="admin-mini-stat">
        <span>Selesai</span>
        <strong>{{ $statSelesai }}</strong>
    </div>
</div>

<div class="admin-tabs-shell mb-3">
    <div class="admin-filter-row admin-filter-row-pengembalian">
        <ul class="nav nav-tabs admin-tabs border-0">
            <li class="nav-item">
                <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('admin.pengembalian.index', ['q' => $search]) }}">
                    <i class="fas fa-list me-1"></i> Semua
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="{{ route('admin.pengembalian.index', ['status' => 'pending', 'q' => $search]) }}">
                    <i class="fas fa-clock me-1"></i> Menunggu
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'ditolak' ? 'active' : '' }}" href="{{ route('admin.pengembalian.index', ['status' => 'ditolak', 'q' => $search]) }}">
                    <i class="fas fa-times-circle me-1"></i> Ditolak
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'selesai' ? 'active' : '' }}" href="{{ route('admin.pengembalian.index', ['status' => 'selesai', 'q' => $search]) }}">
                    <i class="fas fa-check-double me-1"></i> Selesai
                </a>
            </li>
        </ul>

        <a href="{{ route('admin.pengembalian.create') }}" class="btn btn-primary btn-sm admin-action-btn admin-action-btn-compact ms-auto" title="Tambah data pengembalian">
            <i class="fas fa-plus me-1"></i> Tambah
        </a>
        <form action="{{ route('admin.pengembalian.recalculate-denda') }}" method="POST" onsubmit="return confirm('Hitung ulang denda untuk semua data yang sudah selesai?')">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm admin-action-btn admin-action-btn-compact admin-action-btn-tight" title="Hitung ulang denda">
                <i class="fas fa-calculator me-1"></i> Denda
            </button>
        </form>
        <form method="GET" action="{{ route('admin.pengembalian.index') }}" class="admin-inline-search-form ms-1" id="pengembalianSearchForm">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="admin-search-wrap">
                <i class="fas fa-magnifying-glass"></i>
                <input
                    type="text"
                    name="q"
                    id="q"
                    class="form-control admin-search-input"
                    value="{{ $search }}"
                    placeholder="Cari anggota, NISN, ID..."
                    autocomplete="off"
                >
                <button
                    type="button"
                    class="admin-search-clear {{ $search !== '' ? '' : 'd-none' }}"
                    id="pengembalianSearchClear"
                    aria-label="Hapus pencarian"
                >
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 admin-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Anggota</th>
                        <th>Tgl Kembali</th>
                        <th class="text-end" style="width: 140px;">Total Denda</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $index => $item)
                        <tr>
                            <td class="text-center">{{ ($pengembalian->firstItem() ?? 1) + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->peminjaman->anggota->nama }}</div>
                                <small class="text-muted">{{ $item->peminjaman->anggota->nisn }}</small>
                            </td>
                            <td>{{ $item->peminjaman->tanggal_kembali->format('d/m/Y') }}</td>
                            <td class="text-end fw-semibold">
                                @if($item->status === 'selesai')
                                    Rp {{ number_format($item->total_denda ?? 0, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 'pending_admin')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                @elseif($item->status == 'ditolak')
                                    <span class="badge bg-danger"><i class="fas fa-xmark me-1"></i>Ditolak</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Selesai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.pengembalian.edit', $item->id) }}" class="btn btn-sm btn-warning admin-icon-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.pengembalian.show', $item->id) }}" class="btn btn-sm btn-primary admin-icon-btn" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.pengembalian.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger admin-icon-btn" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada data pengembalian</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $pengembalian->links() }}
</div>

<script>
    (function () {
        const form = document.getElementById('pengembalianSearchForm');
        if (!form) return;

        const searchInput = form.querySelector('#q');
        const clearButton = document.getElementById('pengembalianSearchClear');
        if (!searchInput) return;

        let timer = null;
        const toggleClear = function () {
            if (!clearButton) return;
            clearButton.classList.toggle('d-none', searchInput.value.trim() === '');
        };

        searchInput.addEventListener('input', function () {
            if (timer) {
                clearTimeout(timer);
            }

            toggleClear();
            timer = setTimeout(function () {
                form.submit();
            }, 400);
        });

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                toggleClear();
                form.submit();
            });
        }

        toggleClear();
    })();
</script>
@endsection
