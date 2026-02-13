@extends('layouts.admin')

@section('title', 'Edit Peminjaman - Perpustakaan Digital')

@section('header')
<i class="fas fa-edit me-2"></i>Edit Peminjaman
@endsection

@section('content')
<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Peminjaman</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Anggota <span class="text-danger">*</span></label>
                    <select name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ $peminjaman->anggota_id == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama }} ({{ $anggota->nisn }})
                            </option>
                        @endforeach
                    </select>
                    @error('anggota_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                           value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}" required>
                    @error('tanggal_pinjam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Kembali (Batas) <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_kembali" class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                           value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali->format('Y-m-d')) }}" required>
                    @error('tanggal_kembali')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status_pinjam" class="form-select @error('status_pinjam') is-invalid @enderror">
                        <option value="pending" {{ $peminjaman->status_pinjam == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ $peminjaman->status_pinjam == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ $peminjaman->status_pinjam == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @error('status_pinjam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                          rows="3" placeholder="Catatan peminjaman...">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
