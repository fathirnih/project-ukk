<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Perpustakaan Digital')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <div class="sidebar bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="text-center mb-4 fw-bold">
                <i class="fas fa-cogs me-2"></i>Admin Panel
            </h4>
            <nav class="nav flex-column">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white py-2 px-3 rounded mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.buku.index') }}" class="nav-link text-white py-2 px-3 rounded mb-1 {{ request()->routeIs('admin.buku.*') ? 'bg-primary' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-book me-2"></i> Kelola Buku
                </a>
                <a href="{{ route('admin.anggota.index') }}" class="nav-link text-white py-2 px-3 rounded mb-1 {{ request()->routeIs('admin.anggota.*') ? 'bg-primary' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-users me-2"></i> Kelola Anggota
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="nav-link text-white py-2 px-3 rounded mb-1 {{ request()->routeIs('admin.kategori.*') ? 'bg-primary' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tags me-2"></i> Kelola Kategori
                </a>
                <hr class="border-secondary">
                <a href="{{ route('admin.logout') }}" class="nav-link text-white py-2 px-3 rounded mb-1 hover:bg-gray-700">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <hr class="border-secondary">
                <a href="{{ route('home') }}" target="_blank" class="nav-link text-white py-2 px-3 rounded mb-1 hover:bg-gray-700">
                    <i class="fas fa-external-link-alt me-2"></i> Lihat Website
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4 overflow-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h2 class="mb-0 fw-bold">@yield('header', 'Dashboard')</h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">
                        <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                    </span>
                </div>
            </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
