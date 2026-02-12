<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::all();
        $totalAnggota = Anggota::count();
        $denganKelas = Anggota::whereNotNull('kelas')->where('kelas', '!=', '')->count();
        $tanpaAlamat = Anggota::whereNull('alamat')->orWhere('alamat', '')->count();

        return view('admin.anggota.index', compact('anggota', 'totalAnggota', 'denganKelas', 'tanpaAlamat'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:anggota',
            'nama' => 'required',
            'kelas' => 'nullable',
            'alamat' => 'nullable',
        ]);

        Anggota::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function edit(Anggota $anggota)
    {
        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'nisn' => 'required|unique:anggota,nisn,' . $anggota->id,
            'nama' => 'required',
            'kelas' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $anggota->update([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil diperbarui!');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();
        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus!');
    }
}
