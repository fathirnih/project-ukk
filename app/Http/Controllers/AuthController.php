<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'password' => 'required',
        ]);

        $nisn = trim((string) $request->nisn);
        $password = (string) $request->password;
        $anggota = Anggota::where('nisn', $nisn)->first();

        if ($anggota && $anggota->password && Hash::check($password, $anggota->password)) {
            // Logout admin if currently logged in
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            Session::put('anggota_id', $anggota->id);
            Session::put('anggota_nama', $anggota->nama);
            Session::put('anggota_nisn', $anggota->nisn);
            return redirect()->route('peminjaman.index')->with('success', 'Login berhasil!');
        }

        return redirect()->back()->with('error', 'NISN atau password salah!');
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
            'password' => 'required|min:6|confirmed',
            'kelas' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $nisn = trim((string) $request->nisn);
        $nama = $this->toUpper(trim((string) $request->nama));
        $kelas = $request->kelas !== null ? trim((string) $request->kelas) : null;
        $alamat = $request->alamat !== null ? trim((string) $request->alamat) : null;

        Anggota::create([
            'nisn' => $nisn,
            'nama' => $nama,
            'kelas' => $kelas,
            'alamat' => $alamat,
            'password' => Hash::make((string) $request->password),
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('home')->with('success', 'Logout berhasil!');
    }

    private function toUpper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
