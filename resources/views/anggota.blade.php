@extends('layouts.app')

@section('title', 'Profil Anggota - Perpustakaan Digital')

@section('content')
<div class="container">
    <h1 class="mb-4">Profil Anggota</h1>
    <div class="row">
        <div class="col-md-6 mx-auto">
            @if($anggota)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $anggota->nama }}</h5>
                        <hr>
                        <p class="card-text">
                            <strong>NISN:</strong> {{ $anggota->nisn }}<br>
                            <strong>Kelas:</strong> {{ $anggota->kelas ?? '-' }}<br>
                            <strong>Alamat:</strong> {{ $anggota->alamat ?? '-' }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('logout') }}" class="btn btn-danger w-100">Logout</a>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-3">Silakan login untuk melihat profil anggota.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-success">Daftar</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
