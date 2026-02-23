<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan Digital</title>
    @vite(['resources/css/site-theme.css', 'resources/js/app.js'])
</head>
<body class="auth-layout auth-layout-register">
    <div class="container auth-shell">
        <div class="row g-0 auth-frame auth-frame-register">
            <div class="col-lg-5 d-none d-lg-flex auth-brand-panel">
                <div>
                    <span class="auth-pill mb-3 d-inline-flex">Registrasi Anggota</span>
                    <h2 class="fw-bold mb-3">Gabung Perpustakaan Digital</h2>
                    <p class="mb-4 opacity-75">Buat akun anggota untuk menikmati pengalaman peminjaman buku yang cepat dan terorganisir.</p>
                    <div class="auth-brand-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 auth-form-panel">
                <div class="auth-form-card auth-register-card p-4 p-md-4 mx-auto">
                    @include('partials.flash-message')
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h4 class="fw-bold mb-1">Daftar Anggota Baru</h4>
                        <p class="text-muted mb-0">Perpustakaan Digital</p>
                    </div>

                    <form action="{{ route('register.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nisn" class="form-label fw-bold">NISN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nisn" name="nisn" value="{{ old('nisn') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control js-uppercase" id="nama" name="nama" value="{{ old('nama') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label fw-bold">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XII IPA 1">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap Anda">{{ old('alamat') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </button>
                    </form>

                    <div class="text-center small">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none auth-link-accent">Login di sini</a>
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
