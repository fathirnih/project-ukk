<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::all();
        $totalBuku = Buku::count();
        $stokMenipis = Buku::where('jumlah', '<=', 3)->count();
        $tanpaCover = Buku::whereNull('cover')->orWhere('cover', '')->count();

        return view('admin.buku.index', compact('buku', 'totalBuku', 'stokMenipis', 'tanpaCover'));
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
