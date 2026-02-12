<nav class="sidebar">
    @if(session('anggota_id'))
        <div class="sidebar-section-title">Menu Anggota</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                    <i class="fas fa-book-reader me-2"></i> Ajuan Peminjaman
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}" href="{{ route('peminjaman.riwayat') }}">
                    <i class="fas fa-history me-2"></i> Riwayat Peminjaman
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pengembalian.index') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
                    <i class="fas fa-undo me-2"></i> Pengembalian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pengembalian.riwayat') ? 'active' : '' }}" href="{{ route('pengembalian.riwayat') }}">
                    <i class="fas fa-history me-2"></i> Riwayat Pengembalian
                </a>
            </li>
        </ul>
    @endif
</nav>
