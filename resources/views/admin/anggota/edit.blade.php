@extends('layouts.admin')

@section('title', 'Edit Anggota - Admin')
@section('header', 'Edit Anggota')

@section('content')
<div class="d-flex justify-content-between align-items-center admin-section-actions">
    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary admin-action-btn">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="admin-page-intro">
    <h6><i class="fas fa-user-pen me-2"></i>Edit Data Anggota</h6>
    <p>Perbarui data identitas anggota untuk menjaga informasi tetap valid.</p>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Form Edit Anggota
        </h5>
    </div>
    <div class="card-body admin-form-shell">
        <div class="row g-4">
            <div class="col-lg-8">
        <form action="{{ route('admin.anggota.update', ['anggotum' => $anggota->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nisn" class="form-label admin-form-label">NISN <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn', $anggota->nisn) }}" required>
                    @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label admin-form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $anggota->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label admin-form-label">Kelas</label>
                    <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas', $anggota->kelas) }}" placeholder="Contoh: XII IPA 1">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label admin-form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $anggota->alamat) }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label admin-form-label">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label admin-form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="6" placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex admin-form-actions">
                <button type="submit" class="btn btn-warning admin-action-btn">
                    <i class="fas fa-save me-2"></i>Perbarui Anggota
                </button>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary admin-action-btn">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
            </div>
            <div class="col-lg-4">
                <div class="admin-side-card">
                    <h6><i class="fas fa-id-badge me-2"></i>Data Saat Ini</h6>
                    <ul class="admin-side-list">
                        <li>NISN: {{ $anggota->nisn }}</li>
                        <li>Nama: {{ $anggota->nama }}</li>
                        <li>Kelas: {{ $anggota->kelas ?: '-' }}</li>
                    </ul>
                </div>
                <div class="admin-side-card">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                    <p class="mb-0 small text-muted">Gunakan format kelas konsisten agar laporan anggota lebih rapi. Isi password baru hanya jika ingin reset.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
