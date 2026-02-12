@php
    $navbarClass = $navbarClass ?? 'app-navbar';
    $containerClass = $containerClass ?? 'container-fluid px-4';
    $collapseId = $collapseId ?? 'navbarNav';
    $showMemberToggle = $showMemberToggle ?? false;
@endphp

<nav class="navbar navbar-expand-lg {{ $navbarClass }}">
    <div class="{{ $containerClass }}">
        @if($showMemberToggle && session('anggota_id'))
        <button class="btn btn-link text-white d-lg-none me-2" type="button" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        @endif
        <a class="navbar-brand fw-bold text-white d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="nav-brand-icon">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="nav-brand-text">Perpustakaan Digital</span>
        </a>
        <button class="navbar-toggler member-nav-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="{{ $collapseId }}">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 navbar-primary-links">
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
            <ul class="navbar-nav navbar-nav-auth mb-2 mb-lg-0 {{ session('anggota_id') ? '' : 'guest-auth-actions' }}">
                @if(session('anggota_id'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ session('anggota_nama') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end member-nav-menu">
                            <li><a class="dropdown-item" href="{{ route('anggota') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white guest-login-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white guest-register-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> Daftar
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
