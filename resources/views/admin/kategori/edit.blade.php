@extends('layouts.admin')

@section('title', 'Edit Kategori - Admin')
@section('header', 'Edit Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center admin-section-actions">
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary admin-action-btn">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="admin-page-intro">
    <h6><i class="fas fa-tags me-2"></i>Edit Kategori</h6>
    <p>Ubah nama dan deskripsi kategori agar klasifikasi buku tetap konsisten.</p>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Form Edit Kategori
        </h5>
    </div>
    <div class="card-body admin-form-shell">
        <div class="row g-4">
            <div class="col-lg-8">
        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label admin-form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label admin-form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $kategori->keterangan) }}</textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex admin-form-actions">
                <button type="submit" class="btn btn-warning admin-action-btn">
                    <i class="fas fa-save me-2"></i>Perbarui Kategori
                </button>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary admin-action-btn">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
            </div>
            <div class="col-lg-4">
                <div class="admin-side-card">
                    <h6><i class="fas fa-bookmark me-2"></i>Ringkasan</h6>
                    <ul class="admin-side-list">
                        <li>Nama saat ini: {{ $kategori->nama }}</li>
                        <li>Buku terkait: {{ $kategori->buku()->count() }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
