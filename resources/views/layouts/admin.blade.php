<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Perpustakaan Digital')</title>
    <script>
        if (localStorage.getItem('adminSidebarCollapsed') === 'true') {
            document.documentElement.classList.add('admin-sidebar-collapsed');
        }
    </script>
    @vite(['resources/css/site-theme.css', 'resources/js/app.js'])
</head>
<body class="admin-layout">
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <aside id="adminSidebarContainer" class="admin-sidebar text-white">
            <div onclick="toggleAdminSidebar()" class="admin-sidebar-header d-flex align-items-center justify-content-between px-4 py-3">
                <span class="fw-semibold">
                    <i class="fas fa-cogs me-2"></i>Admin Panel
                </span>
                <i id="adminSidebarArrow" class="fas fa-chevron-left"></i>
            </div>
            <nav class="admin-sidebar-nav py-3">
                <div class="px-4 mb-2 admin-sidebar-label fw-semibold">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.buku.index') }}" class="nav-link admin-link {{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                    <i class="fas fa-book text-center"></i>
                    <span>Kelola Buku</span>
                </a>
                <a href="{{ route('admin.anggota.index') }}" class="nav-link admin-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                    <i class="fas fa-users text-center"></i>
                    <span>Kelola Anggota</span>
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="nav-link admin-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                    <i class="fas fa-tags text-center"></i>
                    <span>Kelola Kategori</span>
                </a>
                <a href="{{ route('admin.peminjaman.index') }}" class="nav-link admin-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                    <i class="fas fa-book-reader text-center"></i>
                    <span>Kelola Peminjaman</span>
                </a>
                <a href="{{ route('admin.pengembalian.index') }}" class="nav-link admin-link {{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
                    <i class="fas fa-undo text-center"></i>
                    <span>Konfirmasi Pengembalian</span>
                </a>

                <div class="px-4 mb-2 mt-3 admin-sidebar-label fw-semibold">Sistem</div>
                <a href="{{ route('home') }}" target="_blank" class="nav-link admin-link">
                    <i class="fas fa-external-link-alt text-center"></i>
                    <span>Lihat Website</span>
                </a>
                <a href="{{ route('admin.logout') }}" class="nav-link admin-link admin-link-danger">
                    <i class="fas fa-sign-out-alt text-center"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 admin-topbar">
                <div>
                    <h2 class="mb-0 fw-bold">@yield('header', 'Dashboard')</h2>
                    <small class="admin-topbar-subtext">Perpustakaan Digital Admin Console</small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted admin-user-pill">
                        <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                    </span>
                </div>
            </div>

            <div class="admin-content-surface">
                <!-- Content -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
