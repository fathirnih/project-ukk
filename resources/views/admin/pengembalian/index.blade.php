@extends('layouts.admin')

@section('title', 'Konfirmasi Pengembalian')

@section('header')
<i class="fas fa-undo me-2"></i>Konfirmasi Pengembalian
@endsection

@section('content')
<div class="admin-page-intro">
    <h6><i class="fas fa-clipboard-check me-2"></i>Workflow Pengembalian</h6>
    <p>Validasi pengembalian buku anggota dan pastikan stok kembali ter-update dengan benar.</p>
</div>

<div class="admin-index-overview mb-4">
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

<div class="admin-form-shell mb-4">
    <form method="GET" action="{{ route('admin.pengembalian.index') }}" class="row g-3 align-items-end" id="pengembalianSearchForm">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="col-lg-12">
            <label for="q" class="form-label admin-form-label mb-1">Search</label>
            <input
                type="text"
                id="q"
                name="q"
                class="form-control"
                value="{{ $search }}"
                placeholder="Cari nama anggota, NISN, status, atau tanggal pengajuan..."
                autocomplete="off"
            >
        </div>
    </form>
</div>

<div class="mb-4">
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
</div>

<div class="card admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 admin-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="width: 100px;">Kode</th>
                        <th>Anggota</th>
                        <th>Tgl Pengajuan</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $index => $item)
                        <tr>
                            <td class="text-center">{{ ($pengembalian->firstItem() ?? 1) + $index }}</td>
                            <td class="text-center">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $item->peminjaman->anggota->nama }}</td>
                            <td>{{ $item->tanggal_pengajuan->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if($item->status == 'pending_admin')
                                    <span class="badge bg-info text-dark">Menunggu</span>
                                @elseif($item->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.pengembalian.show', $item->id) }}" class="btn btn-sm btn-primary admin-icon-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
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
        if (!searchInput) return;

        let timer = null;
        searchInput.addEventListener('input', function () {
            if (timer) {
                clearTimeout(timer);
            }

            timer = setTimeout(function () {
                form.submit();
            }, 400);
        });
    })();
</script>
@endsection
