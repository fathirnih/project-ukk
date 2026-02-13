<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $kategoriId = trim((string) $request->get('kategori_id', ''));
        $pengarang = trim((string) $request->get('pengarang', ''));
        $penerbit = trim((string) $request->get('penerbit', ''));
        $stok = trim((string) $request->get('stok', ''));

        $query = Buku::with('kategori');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('judul', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategoriId !== '') {
            $query->where('kategori_id', $kategoriId);
        }

        if ($pengarang !== '') {
            $query->where('pengarang', $pengarang);
        }

        if ($penerbit !== '') {
            $query->where('penerbit', $penerbit);
        }

        if ($stok === 'tersedia') {
            $query->where('jumlah', '>', 0);
        } elseif ($stok === 'menipis') {
            $query->whereBetween('jumlah', [1, 3]);
        } elseif ($stok === 'habis') {
            $query->where('jumlah', '<=', 0);
        }

        $buku = $query->orderBy('judul')->paginate(10)->withQueryString();
        $kategoriOptions = Kategori::orderBy('nama')->get();
        $pengarangOptions = Buku::whereNotNull('pengarang')
            ->where('pengarang', '!=', '')
            ->distinct()
            ->orderBy('pengarang')
            ->pluck('pengarang');
        $penerbitOptions = Buku::whereNotNull('penerbit')
            ->where('penerbit', '!=', '')
            ->distinct()
            ->orderBy('penerbit')
            ->pluck('penerbit');
        $filteredTotal = $buku->total();

        $totalBuku = Buku::count();
        $stokMenipis = Buku::where('jumlah', '<=', 3)->count();
        $tanpaCover = Buku::whereNull('cover')->orWhere('cover', '')->count();

        return view('admin.buku.index', compact(
            'buku',
            'totalBuku',
            'stokMenipis',
            'tanpaCover',
            'kategoriOptions',
            'pengarangOptions',
            'penerbitOptions',
            'filteredTotal',
            'search',
            'kategoriId',
            'pengarang',
            'penerbit',
            'stok'
        ));
    }

    public function create()
    {
        return view('admin.buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isbn' => 'nullable',
            'pengarang' => 'required',
            'penerbit' => 'nullable',
            'tahun_terbit' => 'nullable|numeric',
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jumlah' => 'nullable|numeric',
        ]);

        $data = $request->except('_token', 'cover');

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('covers'), $filename);
            $data['cover'] = $filename;
        }

        Buku::create($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Buku $buku)
    {
        return view('admin.buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required',
            'isbn' => 'nullable',
            'pengarang' => 'required',
            'penerbit' => 'nullable',
            'tahun_terbit' => 'nullable|numeric',
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jumlah' => 'nullable|numeric',
        ]);

        $data = $request->except('_token', 'cover', '_method');

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($buku->cover && file_exists(public_path('covers/' . $buku->cover))) {
                unlink(public_path('covers/' . $buku->cover));
            }

            $file = $request->file('cover');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('covers'), $filename);
            $data['cover'] = $filename;
        }

        $buku->update($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        // Delete cover image if exists
        if ($buku->cover && file_exists(public_path('covers/' . $buku->cover))) {
            unlink(public_path('covers/' . $buku->cover));
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
