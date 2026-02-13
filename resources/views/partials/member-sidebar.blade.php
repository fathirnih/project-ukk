<aside id="sidebarContainer" class="member-sidebar d-none d-lg-flex flex-column text-white">
    <div onclick="toggleSidebar()" class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3">
        <span class="fw-semibold">
            <i class="fas fa-bars me-2"></i>Menu
        </span>
        <i id="sidebarArrow" class="fas fa-chevron-left"></i>
    </div>
    <nav class="py-3">
        <div class="px-4 mb-2 sidebar-label fw-semibold">Menu Utama</div>
        <a class="sidebar-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
            <i class="fas fa-book-reader text-center"></i>
            <span class="sidebar-text">Ajukan Peminjaman</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}" href="{{ route('peminjaman.riwayat') }}">
            <i class="fas fa-history text-center"></i>
            <span class="sidebar-text">Riwayat Peminjaman</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('pengembalian.index') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
            <i class="fas fa-undo text-center"></i>
            <span class="sidebar-text">Pengembalian</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('pengembalian.riwayat') ? 'active' : '' }}" href="{{ route('pengembalian.riwayat') }}">
            <i class="fas fa-history text-center"></i>
            <span class="sidebar-text">Riwayat Pengembalian</span>
        </a>
    </nav>
</aside>

<div id="mobileSidebar" class="d-lg-none" onclick="closeMobileSidebar(event)">
    <div class="mobile-shell text-white" onclick="event.stopPropagation()">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom border-light border-opacity-25">
            <span class="fw-semibold">
                <i class="fas fa-bars me-2"></i>Menu
            </span>
            <button onclick="closeMobileSidebar()" class="btn btn-link text-white p-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="py-3">
            <div class="px-4 mb-2 sidebar-label fw-semibold">Menu Utama</div>
            <a class="sidebar-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}" onclick="closeMobileSidebar()">
                <i class="fas fa-book-reader text-center"></i>
                <span>Aju Peminjaman</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}" href="{{ route('peminjaman.riwayat') }}" onclick="closeMobileSidebar()">
                <i class="fas fa-history text-center"></i>
                <span>Riwayat Peminjaman</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('pengembalian.index') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}" onclick="closeMobileSidebar()">
                <i class="fas fa-undo text-center"></i>
                <span>Pengembalian</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('pengembalian.riwayat') ? 'active' : '' }}" href="{{ route('pengembalian.riwayat') }}" onclick="closeMobileSidebar()">
                <i class="fas fa-history text-center"></i>
                <span>Riwayat Pengembalian</span>
            </a>
        </nav>
    </div>
</div>
