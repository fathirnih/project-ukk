<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    // Helper function untuk memilih layout berdasarkan login
    private function getLayout()
    {
        return Session::has('anggota_id') ? 'layouts.member' : 'layouts.app';
    }

    public function home()
    {
        $featuredBuku = Buku::latest()->take(4)->get();
        return view('home', compact('featuredBuku'));
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function tentang()
    {
        return view('tentang');
    }

    public function koleksi()
    {
        $search = trim((string) request('q', ''));
        $kategoriId = request('kategori_id');
        $pengarang = trim((string) request('pengarang', ''));
        $penerbit = trim((string) request('penerbit', ''));

        $filteredQuery = Buku::with('kategori');

        if ($search !== '') {
            $filteredQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('kategori', function ($kategoriQuery) use ($search) {
                        $kategoriQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($kategoriId)) {
            $filteredQuery->where('kategori_id', $kategoriId);
        }

        if ($pengarang !== '') {
            $filteredQuery->where('pengarang', $pengarang);
        }

        if ($penerbit !== '') {
            $filteredQuery->where('penerbit', $penerbit);
        }

        $stokTersedia = (clone $filteredQuery)->where('jumlah', '>', 0)->count();
        $buku = (clone $filteredQuery)->latest('id')->paginate(20)->withQueryString();

        $kategoriOptions = Kategori::orderBy('nama')->get();
        $pengarangOptions = Buku::query()
            ->whereNotNull('pengarang')
            ->where('pengarang', '!=', '')
            ->distinct()
            ->orderBy('pengarang')
            ->pluck('pengarang');
        $penerbitOptions = Buku::query()
            ->whereNotNull('penerbit')
            ->where('penerbit', '!=', '')
            ->distinct()
            ->orderBy('penerbit')
            ->pluck('penerbit');

        $totalDitampilkan = $buku->total();
        $kategoriAktif = Kategori::count();

        return view('koleksi', compact(
            'buku',
            'kategoriOptions',
            'pengarangOptions',
            'penerbitOptions',
            'totalDitampilkan',
            'kategoriAktif',
            'stokTersedia',
            'search',
            'kategoriId',
            'pengarang',
            'penerbit'
        ));
    }

    public function detailBuku($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku-detail', compact('buku'));
    }

    public function anggota()
    {
        $anggota = null;
        if (Session::has('anggota_id')) {
            $anggota = Anggota::find(Session::get('anggota_id'));
        }
        return view('anggota.index', compact('anggota'));
    }
}
