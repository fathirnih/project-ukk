<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $status = trim((string) $request->get('status', ''));

        $query = Kategori::withCount('buku');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('nama', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($status === 'terpakai') {
            $query->has('buku');
        } elseif ($status === 'kosong') {
            $query->doesntHave('buku');
        }

        $kategori = $query->orderBy('nama')->paginate(10)->withQueryString();
        $filteredTotal = $kategori->total();

        $totalKategori = Kategori::count();
        $totalBukuTerkategori = \App\Models\Buku::whereNotNull('kategori_id')->count();
        $kategoriKosong = Kategori::doesntHave('buku')->count();

        return view('admin.kategori.index', compact(
            'kategori',
            'totalKategori',
            'totalBukuTerkategori',
            'kategoriKosong',
            'filteredTotal',
            'search',
            'status'
        ));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kategori',
            'keterangan' => 'nullable',
        ]);

        Kategori::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|unique:kategori,nama,' . $kategori->id,
            'keterangan' => 'nullable',
        ]);

        $kategori->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
