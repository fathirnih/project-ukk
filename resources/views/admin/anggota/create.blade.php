@extends('layouts.admin')

@section('title', 'Tambah Anggota - Admin')
@section('header', 'Tambah Anggota Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center admin-section-actions">
    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary admin-action-btn">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="admin-page-intro">
    <h6><i class="fas fa-user-plus me-2"></i>Tambah Anggota</h6>
    <p>Masukkan data anggota baru agar bisa login dan melakukan peminjaman.</p>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0">
            <i class="fas fa-user-plus me-2"></i>Form Tambah Anggota
        </h5>
    </div>
    <div class="card-body admin-form-shell">
        <div class="row g-4">
            <div class="col-lg-8">
        <form action="{{ route('admin.anggota.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nisn" class="form-label admin-form-label">NISN <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn') }}" required>
                    @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label admin-form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label admin-form-label">Kelas</label>
                    <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XII IPA 1">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label admin-form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex admin-form-actions">
                <button type="submit" class="btn btn-success admin-action-btn">
                    <i class="fas fa-save me-2"></i>Simpan Anggota
                </button>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary admin-action-btn">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
            </div>
            <div class="col-lg-4">
                <div class="admin-side-card">
                    <h6><i class="fas fa-circle-check me-2"></i>Checklist</h6>
                    <ul class="admin-side-list">
                        <li>NISN dan nama wajib diisi.</li>
                        <li>Isi kelas agar data segmentasi lebih mudah.</li>
                        <li>Alamat membantu validasi profil anggota.</li>
                    </ul>
                </div>
                <div class="admin-side-card">
                    <h6><i class="fas fa-note-sticky me-2"></i>Catatan</h6>
                    <p class="mb-0 small text-muted">Pastikan NISN unik agar tidak bentrok saat login anggota.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
