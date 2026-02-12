@extends('layouts.app')

@section('title', 'Kontak - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-envelope text-primary me-2"></i>Hubungi Kami
        </h1>
        <p class="text-muted">Punya pertanyaan atau masukan? Silakan hubungi kami</p>
    </div>

    <div class="row">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-info-circle me-2"></i>Informasi Kontak
                    </h5>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 me-3" style="font-size: 24px;"></i>
                            <div>
                                <h6 class="mb-1">Alamat</h6>
                                <p class="text-muted mb-0">Jl. Perpustakaan Digital No. 123<br>Jakarta Pusat, 10110</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-envelope text-primary mt-1 me-3" style="font-size: 24px;"></i>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <p class="text-muted mb-0">info@perpustakaan-digital.com</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-phone text-primary mt-1 me-3" style="font-size: 24px;"></i>
                            <div>
                                <h6 class="mb-1">Telepon</h6>
                                <p class="text-muted mb-0">(021) 123-4567</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <h6 class="mb-3">Ikuti Kami</h6>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm me-2">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                    </h5>
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama</label>
                                <input type="text" class="form-control" id="nama" placeholder="Nama Anda">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="email@anda.com">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subjek" class="form-label fw-bold">Subjek</label>
                            <input type="text" class="form-control" id="subjek" placeholder="Subjek pesan">
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label fw-bold">Pesan</label>
                            <textarea class="form-control" id="pesan" rows="5" placeholder="Tulis pesan Anda..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
