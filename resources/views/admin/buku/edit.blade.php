<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .cover-preview {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-3">
                <h4 class="text-center mb-4">🔧 Admin Panel</h4>
                <nav>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.buku.index') }}" class="active">
                        <i class="fas fa-book me-2"></i> Kelola Buku
                    </a>
                    <a href="{{ route('admin.anggota.index') }}">
                        <i class="fas fa-users me-2"></i> Kelola Anggota
                    </a>
                    <hr>
                    <a href="{{ route('admin.logout') }}">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    <hr>
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i> Lihat Website
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Edit Buku</h2>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}" required>
                                            @error('judul')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="isbn" class="form-label">ISBN</label>
                                            <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $buku->isbn) }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pengarang" class="form-label">Pengarang <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('pengarang') is-invalid @enderror" id="pengarang" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required>
                                            @error('pengarang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="penerbit" class="form-label">Penerbit</label>
                                            <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" placeholder="2024">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="jumlah" class="form-label">Jumlah</label>
                                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah', $buku->jumlah) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cover" class="form-label">Cover Buku</label>
                                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                    </div>
                                    @if($buku->cover && file_exists(public_path('covers/' . $buku->cover)))
                                        <div class="mb-3">
                                            <label class="form-label">Cover Saat Ini:</label>
                                            <br>
                                            <img src="{{ asset('covers/' . $buku->cover) }}" alt="{{ $buku->judul }}" class="cover-preview">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Perbarui Buku
                                </button>
                                <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
