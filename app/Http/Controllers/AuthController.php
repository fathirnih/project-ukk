<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'nama' => 'required',
        ]);

        $anggota = Anggota::where('nisn', $request->nisn)
            ->where('nama', $request->nama)
            ->first();

        if ($anggota) {
            Session::put('anggota_id', $anggota->id);
            Session::put('anggota_nama', $anggota->nama);
            Session::put('anggota_nisn', $anggota->nisn);
            return redirect()->route('anggota')->with('success', 'Login berhasil!');
        }

        return redirect()->back()->with('error', 'NISN atau nama tidak ditemukan!');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerProcess(Request $request)
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

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('home')->with('success', 'Logout berhasil!');
    }
}
