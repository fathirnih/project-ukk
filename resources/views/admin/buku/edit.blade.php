@extends('layouts.admin')

@section('title', 'Edit Buku - Admin')
@section('header', 'Edit Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Form Edit Buku
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="judul" class="form-label fw-bold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label fw-bold">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $buku->isbn) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pengarang" class="form-label fw-bold">Pengarang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pengarang') is-invalid @enderror" id="pengarang" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required>
                            @error('pengarang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="penerbit" class="form-label fw-bold">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kategori_id" class="form-label fw-bold">Kategori</label>
                            <select class="form-select" id="kategori_id" name="kategori_id">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(\App\Models\Kategori::all() as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id', $buku->kategori_id) == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tahun_terbit" class="form-label fw-bold">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" placeholder="2024">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="jumlah" class="form-label fw-bold">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah', $buku->jumlah) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cover" class="form-label fw-bold">Cover Buku</label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                    </div>
                    @if($buku->cover && file_exists(public_path('covers/' . $buku->cover)))
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cover Saat Ini:</label>
                            <br>
                            <img src="{{ asset('covers/' . $buku->cover) }}" alt="{{ $buku->judul }}" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Perbarui Buku
                </button>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
