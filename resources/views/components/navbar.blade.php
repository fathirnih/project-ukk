<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.15);">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-white" href="{{ route('home') }}">
            <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
