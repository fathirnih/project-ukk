<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
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

    public function anggota()
    {
        $anggota = null;
        if (Session::has('anggota_id')) {
            $anggota = Anggota::find(Session::get('anggota_id'));
        }
        return view('anggota', compact('anggota'));
    }
}
