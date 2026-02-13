<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BukuSeeder extends Seeder
{
    private array $baseColors = [
        '#0b3c5d',
        '#1f6f8b',
        '#2e8a99',
        '#3b5ba9',
        '#2d6a4f',
        '#6a4c93',
        '#7c3aed',
        '#0f766e',
        '#1d4ed8',
        '#be185d',
        '#c2410c',
        '#334155',
    ];

    private array $accentColors = [
        '#fbbf24',
        '#fde68a',
        '#93c5fd',
        '#a7f3d0',
        '#fca5a5',
        '#c4b5fd',
        '#67e8f9',
        '#86efac',
        '#f9a8d4',
        '#fcd34d',
        '#bfdbfe',
        '#d9f99d',
    ];

    public function run(): void
    {
        $kategoriIds = Kategori::orderBy('id')->pluck('id')->values()->all();
        if (empty($kategoriIds)) {
            return;
        }

        $judulAwal = [
            'Jejak',
            'Misteri',
            'Rahasia',
            'Catatan',
            'Lentera',
            'Jembatan',
            'Peta',
            'Memoar',
            'Ruang',
            'Suara',
            'Gerbang',
            'Titik',
            'Jejak',
            'Langkah',
            'Sisi',
            'Pelangi',
            'Samudra',
            'Nafas',
            'Arah',
            'Kompas',
        ];

        $judulTengah = [
            'Nusantara',
            'Sains',
            'Teknologi',
            'Bisnis',
            'Sejarah',
            'Pendidikan',
            'Kesehatan',
            'Biografi',
            'Fiksi',
            'Inovasi',
            'Kreativitas',
            'Peradaban',
            'Komunitas',
            'Pemimpin',
            'Pemikiran',
            'Digital',
            'Masa Depan',
            'Pengetahuan',
            'Karakter',
            'Inspirasi',
        ];

        $judulAkhir = [
            'Modern',
            'Indonesia',
            'Praktis',
            'Mendalam',
            'Terpadu',
            'Lanjutan',
            'Fundamental',
            'Esensial',
            'Untuk Pelajar',
            'Untuk Pemula',
        ];

        $penulis = [
            'Andi Pratama',
            'Dina Maharani',
            'Rizky Ramadhan',
            'Sinta Aulia',
            'Fajar Nugroho',
            'Nadia Lestari',
            'Bima Saputra',
            'Karina Putri',
            'Yoga Firmansyah',
            'Laila Zahra',
            'Naufal Akbar',
            'Citra Puspita',
            'Reza Maulana',
            'Maya Salsabila',
            'Hana Prameswari',
            'Alif Kurniawan',
            'Rania Putri',
            'Gilang Permana',
            'Putri Nabila',
            'Arga Wibowo',
        ];

        $penerbit = [
            'Bentang Pustaka',
            'Gramedia Pustaka Utama',
            'Erlangga',
            'Mizan',
            'GagasMedia',
            'Republika',
            'Inovasi Press',
            'Literasi Cendekia',
            'Mentari Media',
            'Cakrawala Edukasi',
            'Pustaka Siswa',
            'Bintang Akademia',
        ];

        $buku = [];

        for ($i = 1; $i <= 100; $i++) {
            $awalIndex = ($i - 1) % count($judulAwal);
            $tengahIndex = (($i * 3) - 1) % count($judulTengah);
            $akhirIndex = (($i * 5) - 1) % count($judulAkhir);
            $penulisIndex = ($i - 1) % count($penulis);
            $penerbitIndex = ($i - 1) % count($penerbit);
            $kategoriIndex = ($i - 1) % count($kategoriIds);

            $judul = $judulAwal[$awalIndex] . ' ' . $judulTengah[$tengahIndex] . ' ' . $judulAkhir[$akhirIndex];
            $kodeJudul = str_pad((string)$i, 3, '0', STR_PAD_LEFT);

            $buku[] = [
                'judul' => $judul . ' #' . $kodeJudul,
                'isbn' => sprintf('978-602-%02d-%04d-%d', (($i % 89) + 10), $i, ($i % 9)),
                'pengarang' => $penulis[$penulisIndex],
                'penerbit' => $penerbit[$penerbitIndex],
                'cover' => 'buku-' . $kodeJudul . '.svg',
                'deskripsi' => 'Judul ini membahas topik ' . strtolower($judulTengah[$tengahIndex]) . ' dengan pendekatan praktis, studi kasus, dan rangkuman yang mudah dipahami untuk pembaca.',
                'tahun_terbit' => 2010 + ($i % 15),
                'kategori_id' => $kategoriIds[$kategoriIndex],
                'jumlah' => 2 + ($i % 19),
            ];
        }

        foreach ($buku as $item) {
            Buku::updateOrCreate(
                ['isbn' => $item['isbn']],
                $item
            );
        }

        $this->generateCoverFiles($buku);
    }

    private function generateCoverFiles(array $books): void
    {
        $targets = [
            public_path('covers'),
            public_path('storage/covers'),
        ];

        foreach ($targets as $target) {
            if (!is_dir($target)) {
                mkdir($target, 0755, true);
            }
        }

        foreach ($books as $index => $book) {
            $baseColor = $this->baseColors[$index % count($this->baseColors)];
            $accentColor = $this->accentColors[($index * 2) % count($this->accentColors)];
            $coverContent = $this->buildCoverSvg(
                $book['judul'],
                $book['pengarang'],
                $book['isbn'],
                $baseColor,
                $accentColor
            );

            foreach ($targets as $target) {
                file_put_contents($target . DIRECTORY_SEPARATOR . $book['cover'], $coverContent);
            }
        }
    }

    private function buildCoverSvg(string $title, string $author, string $isbn, string $baseColor, string $accentColor): string
    {
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeAuthor = htmlspecialchars($author, ENT_QUOTES, 'UTF-8');
        $safeIsbn = htmlspecialchars($isbn, ENT_QUOTES, 'UTF-8');

        $wrappedTitle = Str::of($safeTitle)->replace('#', ' #')->explode(' ');
        $lineOne = trim(implode(' ', array_slice($wrappedTitle->all(), 0, 3)));
        $lineTwo = trim(implode(' ', array_slice($wrappedTitle->all(), 3, 3)));
        $lineThree = trim(implode(' ', array_slice($wrappedTitle->all(), 6)));

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="900" viewBox="0 0 600 900">
  <defs>
    <linearGradient id="grad" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$baseColor}" />
      <stop offset="100%" stop-color="#0f172a" />
    </linearGradient>
  </defs>
  <rect width="600" height="900" fill="url(#grad)" />
  <rect x="42" y="42" width="516" height="816" rx="28" fill="none" stroke="rgba(255,255,255,0.28)" stroke-width="4" />
  <circle cx="470" cy="180" r="95" fill="{$accentColor}" fill-opacity="0.25" />
  <text x="80" y="130" fill="#e2e8f0" font-size="24" font-family="Segoe UI, Arial, sans-serif">Perpustakaan Digital</text>
  <text x="80" y="250" fill="white" font-size="48" font-weight="700" font-family="Segoe UI, Arial, sans-serif">{$lineOne}</text>
  <text x="80" y="310" fill="white" font-size="48" font-weight="700" font-family="Segoe UI, Arial, sans-serif">{$lineTwo}</text>
  <text x="80" y="370" fill="white" font-size="40" font-weight="700" font-family="Segoe UI, Arial, sans-serif">{$lineThree}</text>
  <rect x="80" y="620" width="438" height="16" rx="8" fill="rgba(255,255,255,0.30)" />
  <rect x="80" y="654" width="280" height="10" rx="5" fill="rgba(255,255,255,0.20)" />
  <text x="80" y="730" fill="{$accentColor}" font-size="28" font-weight="600" font-family="Segoe UI, Arial, sans-serif">{$safeAuthor}</text>
  <text x="80" y="785" fill="rgba(255,255,255,0.82)" font-size="20" font-family="Segoe UI, Arial, sans-serif">ISBN {$safeIsbn}</text>
</svg>
SVG;
    }
}
