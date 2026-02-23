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
        <a href="{{ route('admin.laporan.index') }}" class="nav-link admin-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <i class="fas fa-file-excel text-center"></i>
            <span>Laporan</span>
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
