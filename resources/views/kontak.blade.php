@extends($app_layout ?? 'layouts.app')

@section('title', 'Kontak - Perpustakaan Digital')

@section('content')
<div class="container py-4">
    <div class="contact-hero mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">
                    <i class="fas fa-envelope me-2"></i>Hubungi Kami
                </h1>
                <p class="mb-0 opacity-75">Punya pertanyaan atau masukan? Tim kami siap membantu Anda.</p>
            </div>
            <div class="col-lg-4">
                <div class="contact-hero-badge">
                    <span>Jam Layanan</span>
                    <strong>08:00 - 17:00</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="card border-0 h-100 contact-info-card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-info-circle me-2"></i>Informasi Kontak
                    </h5>
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                            <div>
                                <h6 class="mb-1">Alamat</h6>
                                <p class="text-muted mb-0">Jl. Perpustakaan Digital No. 123<br>Jakarta Pusat, 10110</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-envelope text-primary mt-1 me-3"></i>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <p class="text-muted mb-0">info@perpustakaan-digital.com</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-phone text-primary mt-1 me-3"></i>
                            <div>
                                <h6 class="mb-1">Telepon</h6>
                                <p class="text-muted mb-0">(021) 123-4567</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <h6 class="mb-3">Ikuti Kami</h6>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2 contact-social-btn">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm me-2 contact-social-btn">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm contact-social-btn">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card border-0 contact-form-card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                    </h5>
                    <form>
                        <div class="contact-form-chip mb-3">
                            <i class="fas fa-shield-heart me-2"></i>Pesan Anda akan kami balas secepatnya.
                        </div>
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
