<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $buku = [
            [
                'judul' => 'Laskar Pelangi',
                'isbn' => '978-979-1224-07-0',
                'pengarang' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'deskripsi' => 'Kisah inspiratif tentang sepuluh anak di sebuah sekolah terpencil di Belitung yang bermimpi menjadi sarjana.',
                'tahun_terbit' => 2005,
                'kategori_id' => 1,
                'jumlah' => 10,
            ],
            [
                'judul' => 'Sang Penulis',
                'isbn' => '978-979-22-3789-8',
                'pengarang' => 'Ahmad Fuadi',
                'penerbit' => 'Gramedia Pustaka Utama',
                'deskripsi' => 'Novel tentang Raka, seorang penulis muda yang berjuang di dunia sastra Indonesia.',
                'tahun_terbit' => 2009,
                'kategori_id' => 1,
                'jumlah' => 5,
            ],
            [
                'judul' => 'Dilan 1990',
                'isbn' => '978-602-03-0032-8',
                'pengarang' => 'Pidi Baiq',
                'penerbit' => 'GagasMedia',
                'deskripsi' => 'Kisah romansa antara Dilan dan Milea yang berlangsung di Bandung tahun 1990.',
                'tahun_terbit' => 2014,
                'kategori_id' => 2,
                'jumlah' => 15,
            ],
            [
                'judul' => 'Rindu',
                'isbn' => '978-979-25-5922-0',
                'pengarang' => 'Tere Liye',
                'penerbit' => 'Republika',
                'deskripsi' => 'Novel yang menceritakan perjalanan hidup dan perjuangan seorang perempuan bernama Rindu.',
                'tahun_terbit' => 2014,
                'kategori_id' => 1,
                'jumlah' => 8,
            ],
            [
                'judul' => 'Bumi',
                'isbn' => '978-602-22-0083-8',
                'pengarang' => 'Tere Liye',
                'penerbit' => 'Gramedia Pustaka Utama',
                'deskripsi' => 'Petualangan Ra, Kiba, dan teman-teman mereka dalam menyelamatkan Bumi dari ancaman alien.',
                'tahun_terbit' => 2014,
                'kategori_id' => 3,
                'jumlah' => 12,
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'isbn' => '978-979-22-4482-0',
                'pengarang' => 'Ahmad Fuadi',
                'penerbit' => 'Gramedia Pustaka Utama',
                'deskripsi' => 'Kisah persahabatan enam mahasiswa dari berbagai daerah di Indonesia.',
                'tahun_terbit' => 2009,
                'kategori_id' => 1,
                'jumlah' => 6,
            ],
        ];

        foreach ($buku as $item) {
            Buku::create($item);
        }
    }
}
