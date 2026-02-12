<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/koleksi', [PageController::class, 'koleksi'])->name('koleksi');
Route::get('/anggota', [PageController::class, 'anggota'])->name('anggota');

// Anggota Auth Routes (Public - NISN + Nama)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Hidden - Login Required)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Buku CRUD
        Route::resource('buku', BukuController::class);

        // Anggota CRUD
        Route::resource('anggota', AnggotaController::class);

        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    });

    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    });
});
