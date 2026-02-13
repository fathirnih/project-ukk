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
        @include('partials.admin-sidebar')

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
