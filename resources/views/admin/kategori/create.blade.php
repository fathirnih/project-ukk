@extends('layouts.admin')

@section('title', 'Tambah Kategori - Admin')
@section('header', 'Tambah Kategori Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center admin-section-actions">
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary admin-action-btn">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="admin-page-intro">
    <h6><i class="fas fa-layer-group me-2"></i>Tambah Kategori</h6>
    <p>Buat kategori baru agar koleksi buku lebih terstruktur dan mudah difilter.</p>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i>Form Tambah Kategori
        </h5>
    </div>
    <div class="card-body admin-form-shell">
        <div class="row g-4">
            <div class="col-lg-8">
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label admin-form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label admin-form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex admin-form-actions">
                <button type="submit" class="btn btn-success admin-action-btn">
                    <i class="fas fa-save me-2"></i>Simpan Kategori
                </button>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary admin-action-btn">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
            </div>
            <div class="col-lg-4">
                <div class="admin-side-card">
                    <h6><i class="fas fa-folder-tree me-2"></i>Guideline</h6>
                    <ul class="admin-side-list">
                        <li>Gunakan nama kategori singkat dan jelas.</li>
                        <li>Hindari kategori duplikat dengan nama mirip.</li>
                        <li>Tulis keterangan untuk konteks pemakaian.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
