<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku - Admin</title>
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
                    <h2>Kelola Buku</h2>
                    <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Tambah Buku
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Cover</th>
                                        <th>Judul</th>
                                        <th>Pengarang</th>
                                        <th>Penerbit</th>
                                        <th>Tahun</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($buku as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if($item->cover && file_exists(public_path('covers/' . $item->cover)))
                                                    <img src="{{ asset('covers/' . $item->cover) }}" alt="{{ $item->judul }}" style="width: 50px; height: 70px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;">
                                                        <i class="fas fa-book text-white"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->judul }}</td>
                                            <td>{{ $item->pengarang }}</td>
                                            <td>{{ $item->penerbit ?? '-' }}</td>
                                            <td>{{ $item->tahun_terbit ?? '-' }}</td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td>
                                                <a href="{{ route('admin.buku.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.buku.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data buku</td>
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
