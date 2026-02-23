<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>
    @vite(['resources/css/site-theme.css', 'resources/js/app.js'])
</head>
<body class="auth-layout auth-layout-login">
    <div class="container auth-shell">
        <div class="row g-0 auth-frame overflow-hidden">
            <div class="col-lg-5 d-none d-lg-flex auth-brand-panel">
                <div>
                    <span class="auth-pill mb-3 d-inline-flex">Portal Anggota</span>
                    <h2 class="fw-bold mb-3">Selamat Datang Kembali</h2>
                    <p class="mb-4 opacity-75">Masuk untuk mengakses koleksi buku, meminjam, dan melihat riwayat aktivitas membaca Anda.</p>
                    <div class="auth-brand-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 auth-form-panel">
                <div class="auth-form-card p-4 p-md-4 mx-auto">
                    @include('partials.flash-message')
                    <div class="text-center mb-4">
                        <i class="fas fa-book-open fa-3x text-primary mb-3"></i>
                        <h4 class="fw-bold mb-1">Login Anggota</h4>
                        <p class="text-muted mb-0">Perpustakaan Digital</p>
                    </div>

                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nisn" class="form-label fw-bold">NISN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control" id="nisn" name="nisn" placeholder="Masukkan NISN" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control js-uppercase" id="nama" name="nama" placeholder="Masukkan Nama" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>

                    <div class="text-center small">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar sekarang</a>
                    </div>
                    <hr>
                    <p class="text-center mb-0">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Website
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.js-uppercase').forEach((element) => {
            element.addEventListener('input', () => {
                element.value = element.value.toUpperCase();
            });
        });
    </script>
</body>
</html>
