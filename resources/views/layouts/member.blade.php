<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital - Anggota')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f5f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .app-container { display: flex; min-height: 100vh; }
        
        /* Navbar */
        .app-navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        
        /* Sidebar */
        .app-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            flex-shrink: 0;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .app-sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 13px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            transition: all 0.2s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .app-sidebar .nav-link i { margin-right: 12px; font-size: 1.1rem; width: 22px; text-align: center; }
        .app-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(3px);
        }
        .app-sidebar .nav-link.active {
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            color: #fff;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.4);
        }
        .app-sidebar-title {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            text-transform: uppercase;
            padding: 20px 20px 10px;
            letter-spacing: 1.5px;
            font-weight: 600;
        }
        
        /* Main Content */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .main-content { flex: 1; padding: 25px; background-color: #f5f7fa; }
        
        /* Cards */
        .custom-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .custom-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        
        /* Footer */
        .app-footer { background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%); }
        
        @media (max-width: 767px) {
            .app-sidebar { position: fixed; top: 56px; left: 0; bottom: 0; z-index: 1000; transform: translateX(-100%); }
            .app-sidebar.show { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('components.navbar')

    <div class="app-container">
        <!-- Sidebar -->
        <nav class="app-sidebar">
            <div class="pt-3">
                <div class="app-sidebar-title">Menu Utama</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                            <i class="fas fa-book-reader"></i> Ajuan Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}" href="{{ route('peminjaman.riwayat') }}">
                            <i class="fas fa-history"></i> Riwayat Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengembalian.index') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
                            <i class="fas fa-undo"></i> Pengembalian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengembalian.riwayat') ? 'active' : '' }}" href="{{ route('pengembalian.riwayat') }}">
                            <i class="fas fa-history"></i> Riwayat Pengembalian
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-wrapper">
            <main class="main-content">@yield('content')</main>
            
            <!-- Footer -->
            <footer class="app-footer text-white py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h5 class="fw-bold"><i class="fas fa-book-open me-2"></i>Perpustakaan Digital</h5>
                            <p class="mb-0 text-white-50">Menyediakan akses mudah ke berbagai koleksi buku.</p>
                        </div>
                        <div class="col-md-6 text-md-end"><p class="mb-0">&copy; {{ date('Y') }} Perpustakaan Digital</p></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
