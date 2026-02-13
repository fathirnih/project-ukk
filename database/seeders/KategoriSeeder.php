<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'Fiksi',
                'keterangan' => 'Buku-buku cerita fiksi dan novel',
            ],
            [
                'nama' => 'Non-Fiksi',
                'keterangan' => 'Buku-buku berdasarkan fakta dan informasi',
            ],
            [
                'nama' => 'Sains',
                'keterangan' => 'Buku-buku tentang ilmu pengetahuan',
            ],
            [
                'nama' => 'Sejarah',
                'keterangan' => 'Buku-buku tentang sejarah dan peristiwa masa lalu',
            ],
            [
                'nama' => 'Pendidikan',
                'keterangan' => 'Buku-buku pelajaran dan pendidikan',
            ],
            [
                'nama' => 'Agama',
                'keterangan' => 'Buku-buku tentang keagamaan dan spiritual',
            ],
            [
                'nama' => 'Teknologi',
                'keterangan' => 'Buku-buku tentang komputer, internet, dan inovasi teknologi',
            ],
            [
                'nama' => 'Bisnis',
                'keterangan' => 'Buku-buku tentang kewirausahaan, manajemen, dan ekonomi',
            ],
            [
                'nama' => 'Biografi',
                'keterangan' => 'Kisah hidup tokoh inspiratif dari berbagai bidang',
            ],
            [
                'nama' => 'Kesehatan',
                'keterangan' => 'Buku-buku tentang kesehatan, kebugaran, dan pola hidup sehat',
            ],
        ];

        foreach ($kategori as $kat) {
            Kategori::updateOrCreate(
                ['nama' => $kat['nama']],
                $kat
            );
        }
    }
}
