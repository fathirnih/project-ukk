<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Anggota Ditolak - Perpustakaan Digital</title>
    @vite(['resources/css/site-theme.css', 'resources/js/app.js'])
</head>
<body class="auth-layout access-layout">
    <div class="container access-shell">
        <div class="access-card text-center mx-auto">
            <div class="access-icon warning">
                <i class="fas fa-user-lock"></i>
            </div>
            <div class="access-code">403</div>
            <h1 class="access-title">Akses Anggota Ditolak</h1>
            <p class="access-text">Akun admin tidak dapat membuka halaman anggota. Silakan login sebagai anggota untuk melanjutkan.</p>
            <div class="access-actions">
                <a href="{{ route('login') }}" class="btn btn-primary px-4">
                    <i class="fas fa-sign-in-alt me-2"></i>Login Anggota
                </a>
                <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-user-shield me-2"></i>Login Admin
                </a>
            </div>
        </div>
    </div>
</body>
</html>
