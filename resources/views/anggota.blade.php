@extends('layouts.app')

@section('title', 'Profil Anggota - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    @if($anggota)
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2"></i>Profil Anggota
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                            <h4 class="fw-bold mb-0">{{ $anggota->nama }}</h4>
                            @if($anggota->kelas)
                                <span class="badge bg-info mt-2">{{ $anggota->kelas }}</span>
                            @endif
                        </div>
                        <hr>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-bold" style="width: 120px;">
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
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>Alamat
                                </td>
                                <td>: {{ $anggota->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('koleksi') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-book me-2"></i>Koleksi
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
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
                <div class="card border-0 shadow-sm text-center p-5">
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
