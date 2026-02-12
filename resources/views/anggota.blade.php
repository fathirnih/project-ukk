@extends('layouts.member')

@section('title', 'Profil Anggota - Perpustakaan Digital')

@section('content')
<div class="container-fluid">
    @if($anggota)
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card card-custom">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2"></i>Profil Anggota
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-4x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">{{ $anggota->nama }}</h4>
                            @if($anggota->kelas)
                                <span class="badge bg-info mt-2">{{ $anggota->kelas }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" style="width: 100px;">
                                            <i class="fas fa-id-card me-2 text-primary"></i>NISN
                                        </td>
                                        <td>: {{ $anggota->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">
                                            <i class="fas fa-graduation-cap me-2 text-primary"></i>Kelas
                                        </td>
                                        <td>: {{ $anggota->kelas ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" style="width: 100px;">
                                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>Alamat
                                        </td>
                                        <td>: {{ $anggota->alamat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">
                                            <i class="fas fa-phone me-2 text-primary"></i>No. HP
                                        </td>
                                        <td>: {{ $anggota->no_hp ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('koleksi') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-book me-2"></i>Lihat Koleksi
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-success w-100">
                                    <i class="fas fa-book-reader me-2"></i>Ajuan Peminjaman
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-custom text-center p-5">
                    <i class="fas fa-user-circle fa-5x text-muted mb-4"></i>
                    <h4 class="mb-3">Silakan Login</h4>
                    <p class="text-muted mb-4">Login untuk melihat profil anggota Anda.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
