@extends('layouts.app')

@section('title', 'Kontak - Perpustakaan Digital')

@section('content')
<div class="container">
    <h1 class="mb-4">Hubungi Kami</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Kontak</h5>
                    <p class="card-text">
                        <strong>Alamat:</strong><br>
                        Jl. Perpustakaan Digital No. 123<br>
                        Jakarta Pusat, 10110<br><br>
                        <strong>Email:</strong><br>
                        info@perpustakaan-digital.com<br><br>
                        <strong>Telepon:</strong><br>
                        (021) 123-4567
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <form>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" placeholder="Nama Anda">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="email@anda.com">
                </div>
                <div class="mb-3">
                    <label for=" pesan" class="form-label">Pesan</label>
                    <textarea class="form-control" id="pesan" rows="4" placeholder="Tulis pesan Anda..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </form>
        </div>
    </div>
</div>
@endsection
