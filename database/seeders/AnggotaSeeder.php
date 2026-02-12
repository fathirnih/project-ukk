<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anggota = [
            [
                'nisn' => '111111',
                'nama' => 'Andi Prasetyo',
                'kelas' => 'XII IPA 1',
                'alamat' => 'Jl. Sudirman No. 1, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '222222',
                'nama' => 'Siti Aminah',
                'kelas' => 'XII IPA 2',
                'alamat' => 'Jl. Thamrin No. 2, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '333333',
                'nama' => 'Budi Santoso',
                'kelas' => 'XI IPS 1',
                'alamat' => 'Jl. Gatot Subroto No. 3, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '444444',
                'nama' => 'Dewi Lestari',
                'kelas' => 'X IPA 1',
                'alamat' => 'Jl. Asia Afrika No. 4, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '555555',
                'nama' => 'Rendi Kurniawan',
                'kelas' => 'XII IPS 2',
                'alamat' => 'Jl. Merdeka No. 5, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('anggota')->insert($anggota);
    }
}
