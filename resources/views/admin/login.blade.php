<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Perpustakaan Digital</title>
    @vite(['resources/css/site-theme.css', 'resources/js/app.js'])
</head>
<body class="admin-login-layout">
    <div class="container admin-login-shell">
        <div class="row g-0 admin-login-frame overflow-hidden">
            <div class="col-lg-5 d-none d-lg-flex admin-login-brand">
                <div>
                    <span class="admin-login-pill mb-3 d-inline-flex">Admin Panel</span>
                    <h2 class="fw-bold mb-3">Akses Dashboard</h2>
                    <p class="mb-4 opacity-75">Login sebagai administrator untuk mengelola buku, anggota, kategori, serta proses peminjaman dan pengembalian.</p>
                    <div class="admin-login-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 admin-login-form-panel">
                <div class="admin-login-card">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-lock me-2"></i>Admin Login
                    </h4>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('admin.login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">Login</button>
                    </form>
                    <hr>
                    <a href="{{ route('home') }}" class="text-muted small text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
