<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\AdminPengembalianController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\MemberAuth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/koleksi', [PageController::class, 'koleksi'])->name('koleksi');
Route::get('/buku/{id}', [PageController::class, 'detailBuku'])->name('buku.detail');
Route::get('/anggota', [PageController::class, 'anggota'])->name('anggota');

// Peminjaman Routes (Protected - untuk anggota)
Route::middleware([MemberAuth::class])->prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::post('/', [PeminjamanController::class, 'store'])->name('store');
    Route::get('/ajuan-kembali/{id}', [PeminjamanController::class, 'ajukanKembali'])->name('ajuan-kembali');
    Route::get('/ajukan-ulang/{id}', [PeminjamanController::class, 'ajukanUlang'])->name('ajukan-ulang');
    Route::get('/riwayat', [PeminjamanController::class, 'riwayatPeminjaman'])->name('riwayat');
});

// Pengembalian Routes (Protected - untuk anggota)
Route::middleware([MemberAuth::class])->prefix('pengembalian')->name('pengembalian.')->group(function () {
    Route::get('/', [PengembalianController::class, 'index'])->name('index');
    Route::get('/riwayat', [PengembalianController::class, 'riwayat'])->name('riwayat');
    Route::get('/ajukan-ulang/{id}', [PeminjamanController::class, 'ajukanUlangPengembalian'])->name('ajukan-ulang');
});

// Admin Routes (Hidden)
Route::prefix('admin')->name('admin.')->group(function () {
    // Login - accessible to everyone (controller handles redirect if already logged in)
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    // Protected routes - requires admin login
    Route::middleware([AdminAuth::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Peminjaman CRUD
        Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/create', [AdminPeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [AdminPeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{id}/edit', [AdminPeminjamanController::class, 'edit'])->name('peminjaman.edit');
        Route::put('/peminjaman/{id}', [AdminPeminjamanController::class, 'update'])->name('peminjaman.update');
        Route::delete('/peminjaman/{id}', [AdminPeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
        Route::get('/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{id}/setujui', [AdminPeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::post('/peminjaman/{id}/tolak', [AdminPeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        Route::post('/peminjaman/{id}/konfirmasi-kembali', [AdminPeminjamanController::class, 'konfirmasiKembali'])->name('peminjaman.konfirmasi-kembali');
        Route::post('/peminjaman/{id}/tolak-kembali', [AdminPeminjamanController::class, 'tolakKembali'])->name('peminjaman.tolak-kembali');

        // Pengembalian CRUD
        Route::get('/pengembalian', [AdminPengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('/pengembalian/create', [AdminPengembalianController::class, 'create'])->name('pengembalian.create');
        Route::post('/pengembalian', [AdminPengembalianController::class, 'store'])->name('pengembalian.store');
        Route::get('/pengembalian/{id}', [AdminPengembalianController::class, 'show'])->name('pengembalian.show');
        Route::get('/pengembalian/{id}/edit', [AdminPengembalianController::class, 'edit'])->name('pengembalian.edit');
        Route::put('/pengembalian/{id}', [AdminPengembalianController::class, 'update'])->name('pengembalian.update');
        Route::delete('/pengembalian/{id}', [AdminPengembalianController::class, 'destroy'])->name('pengembalian.destroy');
        Route::post('/pengembalian/{id}/konfirmasi', [AdminPengembalianController::class, 'konfirmasi'])->name('pengembalian.konfirmasi');
        Route::post('/pengembalian/{id}/tolak', [AdminPengembalianController::class, 'tolak'])->name('pengembalian.tolak');
        Route::post('/pengembalian/recalculate-denda', [AdminPengembalianController::class, 'recalculateDenda'])->name('pengembalian.recalculate-denda');

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
