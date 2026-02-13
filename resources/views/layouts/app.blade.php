<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    @vite(['resources/css/site-theme.css', 'resources/js/app.js'])
</head>
<body class="app-layout">
    <!-- Navbar Component -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="app-footer mt-4">
        <div class="container">
            <div class="app-footer-top">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
                        </h5>
                        <p class="app-footer-text mb-3">Menyediakan akses mudah dan cepat ke koleksi buku berkualitas untuk mendukung budaya literasi.</p>
                        <div class="app-footer-social">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <h6 class="app-footer-title">Navigasi</h6>
                        <ul class="app-footer-links list-unstyled mb-0">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('koleksi') }}">Koleksi</a></li>
                            <li><a href="{{ route('tentang') }}">Tentang</a></li>
                            <li><a href="{{ route('kontak') }}">Kontak</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <h6 class="app-footer-title">Kontak</h6>
                        <ul class="app-footer-meta list-unstyled mb-0">
                            <li><i class="fas fa-envelope me-2"></i>info@perpustakaan-digital.com</li>
                            <li><i class="fas fa-phone me-2"></i>(021) 123-4567</li>
                            <li><i class="fas fa-location-dot me-2"></i>Jakarta Pusat</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="app-footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <small>&copy; {{ date('Y') }} Perpustakaan Digital</small>
                <small>Built for better reading experience</small>
            </div>
        </div>
    </footer>
</body>
</html>
