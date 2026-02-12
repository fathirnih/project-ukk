@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')

@section('header')
    Kelola Peminjaman
@endsection

@section('content')
<div class="mb-4">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}">Semua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}">Menunggu Persetujuan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'menunggu_kembali' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'menunggu_kembali']) }}">Sedang Dipinjam</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'menunggu_kembali_admin' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'menunggu_kembali_admin']) }}">Menunggu Pengembalian</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'selesai' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['status' => 'selesai']) }}">Selesai</a>
        </li>
    </ul>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Anggota</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status Pinjam</th>
                    <th>Status Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $index => $peminjaman)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $peminjaman->anggota->nama }}</strong><br>
                            <small class="text-muted">{{ $peminjaman->anggota->nisn }}</small>
                        </td>
                        <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $peminjaman->tanggal_kembali->format('d/m/Y') }}</td>
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
                            <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $peminjamans->links() }}
    </div>
</div>
@endsection
