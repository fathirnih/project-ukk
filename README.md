# Perpustakaan Digital (Project UKK SMK)

Aplikasi web perpustakaan digital berbasis Laravel untuk kebutuhan Ujian Kompetensi Keahlian (UKK).

## Fitur Utama

### Public
- Home, Koleksi, Tentang, Kontak
- Detail buku

### Anggota
- Register anggota
- Login anggota (NISN + Nama)
- Ajukan peminjaman buku (multi buku dalam satu transaksi)
- Riwayat peminjaman
- Ajukan pengembalian
- Riwayat pengembalian

### Admin
- Login admin
- Dashboard ringkasan
- Kelola Buku (CRUD)
- Kelola Kategori (CRUD)
- Kelola Anggota (CRUD)
- Kelola Peminjaman (setujui/tolak)
- Konfirmasi Pengembalian (konfirmasi/tolak)

## Teknologi
- Laravel 12
- PHP 8.4
- MySQL
- Blade
- Bootstrap + custom CSS
- Vite

## Struktur Data Inti

- `peminjamans`: header transaksi peminjaman
- `detail_peminjamans`: item buku per transaksi
- `pengembalians`: proses pengembalian per transaksi

Relasi utama:
- `peminjamans.id` -> `detail_peminjamans.peminjaman_id`
- `peminjamans.id` -> `pengembalians.peminjaman_id`

## Akun Demo Seeder

### Admin
- Email: `admin@perpus.com`
- Password: `admin123`

### Anggota
Login anggota pakai kombinasi NISN + Nama, contoh:
- NISN: `111111`
- Nama: `Andi Prasetyo`

Seeder anggota lain juga tersedia di `database/seeders/AnggotaSeeder.php`.

## Cara Menjalankan Setelah Clone

1. Install dependency PHP
```bash
composer install
```

2. Install dependency frontend
```bash
npm install
```

3. Copy env
```bash
cp .env.example .env
```

4. Generate app key
```bash
php artisan key:generate
```

5. Atur koneksi database di `.env`

6. Migrasi + seeding
```bash
php artisan migrate:fresh --seed
```

7. Jalankan server Laravel
```bash
php artisan serve
```

8. Jalankan Vite
```bash
npm run dev
```

Akses aplikasi:
- Website: `http://127.0.0.1:8000`
- Admin login: `http://127.0.0.1:8000/admin/login`

Project ini dibuat untuk kebutuhan UKK SMK dan bisa dikembangkan lebih lanjut untuk skenario sekolah nyata.
