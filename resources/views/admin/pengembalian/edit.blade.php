@extends('layouts.admin')

@section('title', 'Edit Pengembalian - Perpustakaan Digital')

@section('header')
<i class="fas fa-edit me-2"></i>Edit Pengembalian
@endsection

@section('content')
<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Pengembalian</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pengembalian.update', $pengembalian->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peminjaman <span class="text-danger">*</span></label>
                    <select name="peminjaman_id" class="form-select @error('peminjaman_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Peminjaman --</option>
                        @foreach($peminjamans as $peminjaman)
                            <option value="{{ $peminjaman->id }}" {{ $pengembalian->peminjaman_id == $peminjaman->id ? 'selected' : '' }}>
                                #{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }} - {{ $peminjaman->anggota->nama }} ({{ $peminjaman->anggota->nisn }})
                            </option>
                        @endforeach
                    </select>
                    @error('peminjaman_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Pengajuan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pengajuan" class="form-control @error('tanggal_pengajuan') is-invalid @enderror" 
                           value="{{ old('tanggal_pengajuan', $pengembalian->tanggal_pengajuan->format('Y-m-d')) }}" required>
                    @error('tanggal_pengajuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending_admin" {{ $pengembalian->status == 'pending_admin' ? 'selected' : '' }}>Menunggu</option>
                        <option value="selesai" {{ $pengembalian->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ $pengembalian->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hari Terlambat</label>
                    <input type="number" name="hari_terlambat" class="form-control @error('hari_terlambat') is-invalid @enderror" 
                           value="{{ old('hari_terlambat', $pengembalian->hari_terlambat ?? 0) }}" min="0">
                    @error('hari_terlambat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Denda Per Hari (Rp)</label>
                    <input type="number" name="denda_per_hari" class="form-control @error('denda_per_hari') is-invalid @enderror" 
                           value="{{ old('denda_per_hari', $pengembalian->denda_per_hari ?? config('perpustakaan.denda_per_hari', 1000)) }}" min="0">
                    @error('denda_per_hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Total Denda (Rp)</label>
                <input type="number" name="total_denda" class="form-control @error('total_denda') is-invalid @enderror" 
                       value="{{ old('total_denda', $pengembalian->total_denda ?? 0) }}" min="0">
                @error('total_denda')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Catatan Penolakan</label>
                <textarea name="catatan_penolakan" class="form-control @error('catatan_penolakan') is-invalid @enderror" 
                          rows="3" placeholder="Masukkan alasan jika ditolak...">{{ old('catatan_penolakan', $pengembalian->catatan_penolakan) }}</textarea>
                @error('catatan_penolakan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
