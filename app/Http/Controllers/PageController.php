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
        $buku = Buku::all();
        return view('koleksi', compact('buku'));
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
