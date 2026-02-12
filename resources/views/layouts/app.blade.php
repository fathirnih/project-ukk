<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- Navbar Component -->
    @include('components.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="fw-bold">
                        <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
                    </h5>
                    <p class="mb-0 text-muted">Menyediakan akses mudah dan cepat ke berbagai koleksi buku dari seluruh dunia.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="{{ route('koleksi') }}" class="text-white text-decoration-none">Koleksi Buku</a></li>
                        <li><a href="{{ route('tentang') }}" class="text-white text-decoration-none">Tentang Kami</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-3 border-secondary">
            <p class="text-center mb-0">
                &copy; {{ date('Y') }} Perpustakaan Digital. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
