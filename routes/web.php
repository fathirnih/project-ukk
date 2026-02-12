<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/koleksi', [PageController::class, 'koleksi'])->name('koleksi');
Route::get('/buku/{id}', [PageController::class, 'detailBuku'])->name('buku.detail');
Route::get('/anggota', [PageController::class, 'anggota'])->name('anggota');

// Admin Routes (Hidden)
Route::prefix('admin')->name('admin.')->group(function () {
    // Login - accessible to everyone (controller handles redirect if already logged in)
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    // Protected routes - requires admin login
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Buku CRUD
        Route::resource('buku', BukuController::class);

        // Anggota CRUD
        Route::resource('anggota', AnggotaController::class);

        // Kategori CRUD
        Route::resource('kategori', KategoriController::class);

        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

// Anggota Auth Routes (Public - NISN + Nama)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
