<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak Admin - Perpustakaan Digital</title>
    @vite(['resources/css/site-theme.css', 'resources/js/app.js'])
</head>
<body class="auth-layout access-layout">
    <div class="container access-shell">
        <div class="access-card text-center mx-auto">
            <div class="access-icon danger">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div class="access-code">403</div>
            <h1 class="access-title">Akses Admin Ditolak</h1>
            <p class="access-text">Akun anggota tidak dapat membuka halaman admin. Silakan login menggunakan akun admin.</p>
            <div class="access-actions">
                <a href="{{ route('admin.login') }}" class="btn btn-primary px-4">
                    <i class="fas fa-user-shield me-2"></i>Login Admin
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
