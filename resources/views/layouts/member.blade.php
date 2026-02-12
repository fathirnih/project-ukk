<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital - Anggota')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { background-color: #f5f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .sidebar-container {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            min-height: calc(100vh - 56px);
            transition: width 0.3s ease;
            position: sticky;
            top: 56px;
        }
        .sidebar-container.collapsed { width: 70px; }
        
        .sidebar-link {
            color: rgba(255,255,255,0.85);
            padding: 12px 16px;
            border-radius: 8px;
            margin: 2px 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }
        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar-link.active {
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            color: #fff;
        }
        .sidebar-link i { width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-container.collapsed .sidebar-link { justify-content: center; padding: 12px; }
        .sidebar-container.collapsed .sidebar-link i { margin-right: 0; }
        .sidebar-container.collapsed .sidebar-link span { display: none; }
        
        .sidebar-title {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            text-transform: uppercase;
            padding: 20px 16px 10px;
            letter-spacing: 1.5px;
            font-weight: 600;
            white-space: nowrap;
        }
        .sidebar-container.collapsed .sidebar-title { display: none; }
        
        .toggle-sidebar {
            padding: 15px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.2s;
        }
        .toggle-sidebar:hover { background-color: rgba(255,255,255,0.05); }
        .sidebar-container.collapsed .toggle-sidebar { justify-content: center; padding: 15px; }
        .sidebar-container.collapsed .toggle-sidebar span { display: none; }
        
        .main-content {
            flex: 1;
            transition: margin-left 0.3s ease;
            width: 100%;
        }
        .main-content.expanded { margin-left: 0; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.15); z-index: 1030;">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-white" href="{{ route('home') }}">
                <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('koleksi') ? 'active' : '' }}" href="{{ route('koleksi') }}">
                            <i class="fas fa-book me-1"></i> Koleksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">
                            <i class="fas fa-info-circle me-1"></i> Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('kontak') ? 'active' : '' }}" href="{{ route('kontak') }}">
                            <i class="fas fa-envelope me-1"></i> Kontak
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    @if(session('anggota_id'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ session('anggota_nama') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('anggota') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Daftar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="d-flex" style="padding-top: 56px;">
        @if(session('anggota_id'))
        <!-- Sidebar -->
        <div class="sidebar-container flex-shrink-0" id="sidebarContainer">
            <div class="toggle-sidebar" onclick="toggleSidebar()">
                <span><i class="fas fa-bars me-2"></i>Menu</span>
                <i class="fas fa-chevron-left" id="sidebarArrow" style="transition: transform 0.3s;"></i>
            </div>
            <nav class="nav flex-column py-3">
                <div class="sidebar-title">Menu Utama</div>
                <a class="sidebar-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                    <i class="fas fa-book-reader"></i> <span>Aju Peminjaman</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}" href="{{ route('peminjaman.riwayat') }}">
                    <i class="fas fa-history"></i> <span>Riwayat Peminjaman</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('pengembalian.index') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
                    <i class="fas fa-undo"></i> <span>Pengembalian</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('pengembalian.riwayat') ? 'active' : '' }}" href="{{ route('pengembalian.riwayat') }}">
                    <i class="fas fa-history"></i> <span>Riwayat Pengembalian</span>
                </a>
            </nav>
        </div>
        @endif
        
        <!-- Main Content -->
        <main class="main-content p-4" id="mainContent">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Restore sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebarContainer');
            const arrow = document.getElementById('sidebarArrow');
            
            if (sidebar && localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
                if (arrow) arrow.style.transform = 'rotate(-90deg)';
            }
        });
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarContainer');
            const arrow = document.getElementById('sidebarArrow');
            
            sidebar.classList.toggle('collapsed');
            
            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            
            // Rotate arrow
            if (sidebar.classList.contains('collapsed')) {
                arrow.style.transform = 'rotate(-90deg)';
            } else {
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</body>
</html>
