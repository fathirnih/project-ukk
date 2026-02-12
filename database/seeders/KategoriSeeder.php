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
        ];

        foreach ($kategori as $kat) {
            Kategori::create($kat);
        }
    }
}
