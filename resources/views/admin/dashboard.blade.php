<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan Digital</title>
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-3">
                <h4 class="text-center mb-4">🔧 Admin Panel</h4>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="active">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.buku.index') }}">
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
                    <h2>Dashboard Admin</h2>
                    <span class="text-muted">Selamat datang, {{ Auth::user()->name }}</span>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Buku</h5>
                                <h2>{{ \App\Models\Buku::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Anggota</h5>
                                <h2>{{ \App\Models\Anggota::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title">Buku Dipinjam</h5>
                                <h2>0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Admin Aktif</h5>
                                <h2>1</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Menu Utama</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.buku.create') }}" class="btn btn-primary w-100 py-3">
                                    <i class="fas fa-plus-circle me-2"></i> Tambah Buku
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.buku.index') }}" class="btn btn-info w-100 py-3">
                                    <i class="fas fa-book me-2"></i> Daftar Buku
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.anggota.create') }}" class="btn btn-success w-100 py-3">
                                    <i class="fas fa-user-plus me-2"></i> Tambah Anggota
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary w-100 py-3">
                                    <i class="fas fa-users me-2"></i> Daftar Anggota
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Books -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Buku Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Pengarang</th>
                                        <th>Penerbit</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(\App\Models\Buku::latest()->take(5)->get() as $buku)
                                        <tr>
                                            <td>{{ $buku->judul }}</td>
                                            <td>{{ $buku->pengarang }}</td>
                                            <td>{{ $buku->penerbit ?? '-' }}</td>
                                            <td>{{ $buku->jumlah }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada buku</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
