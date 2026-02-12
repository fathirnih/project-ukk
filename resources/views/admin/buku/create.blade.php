@extends('layouts.admin')

@section('title', 'Tambah Buku - Admin')
@section('header', 'Tambah Buku Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center admin-section-actions">
    <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary admin-action-btn">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="admin-page-intro">
    <h6><i class="fas fa-book-medical me-2"></i>Tambah Buku Baru</h6>
    <p>Lengkapi data buku dengan cover dan kategori agar mudah ditemukan anggota.</p>
</div>

<div class="card admin-card">
    <div class="card-header admin-card-header">
        <h5 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i>Form Tambah Buku
        </h5>
    </div>
    <div class="card-body admin-form-shell">
        <div class="row g-4">
            <div class="col-lg-8">
        <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="judul" class="form-label admin-form-label">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="isbn" class="form-label admin-form-label">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pengarang" class="form-label admin-form-label">Pengarang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('pengarang') is-invalid @enderror" id="pengarang" name="pengarang" value="{{ old('pengarang') }}" required>
                    @error('pengarang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="penerbit" class="form-label admin-form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="kategori_id" class="form-label admin-form-label">Kategori</label>
                    <select class="form-select" id="kategori_id" name="kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(\App\Models\Kategori::all() as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tahun_terbit" class="form-label admin-form-label">Tahun Terbit</label>
                    <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit') }}" placeholder="2024">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="jumlah" class="form-label admin-form-label">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="cover" class="form-label admin-form-label">Cover Buku</label>
                <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label admin-form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex admin-form-actions">
                <button type="submit" class="btn btn-primary admin-action-btn">
                    <i class="fas fa-save me-2"></i>Simpan Buku
                </button>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary admin-action-btn">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
            </div>
            <div class="col-lg-4">
                <div class="admin-side-card">
                    <h6><i class="fas fa-circle-check me-2"></i>Checklist</h6>
                    <ul class="admin-side-list">
                        <li>Judul dan pengarang wajib diisi.</li>
                        <li>Pilih kategori agar katalog terstruktur.</li>
                        <li>Upload cover agar tampilan koleksi lebih menarik.</li>
                    </ul>
                </div>
                <div class="admin-side-card">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                    <p class="mb-0 small text-muted">Gunakan jumlah awal sesuai stok fisik agar data peminjaman tetap akurat.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
