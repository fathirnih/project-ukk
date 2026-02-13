<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $kelas = trim((string) $request->get('kelas', ''));
        $alamatStatus = trim((string) $request->get('alamat', ''));

        $query = Anggota::query();

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('nisn', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($kelas !== '') {
            $query->where('kelas', $kelas);
        }

        if ($alamatStatus === 'lengkap') {
            $query->whereNotNull('alamat')->where('alamat', '!=', '');
        } elseif ($alamatStatus === 'kosong') {
            $query->where(function ($subQuery) {
                $subQuery->whereNull('alamat')->orWhere('alamat', '');
            });
        }

        $anggota = $query->orderBy('nama')->paginate(10)->withQueryString();
        $kelasOptions = Anggota::whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');
        $filteredTotal = $anggota->total();

        $totalAnggota = Anggota::count();
        $denganKelas = Anggota::whereNotNull('kelas')->where('kelas', '!=', '')->count();
        $tanpaAlamat = Anggota::whereNull('alamat')->orWhere('alamat', '')->count();

        return view('admin.anggota.index', compact(
            'anggota',
            'totalAnggota',
            'denganKelas',
            'tanpaAlamat',
            'kelasOptions',
            'filteredTotal',
            'search',
            'kelas',
            'alamatStatus'
        ));
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
