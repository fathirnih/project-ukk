<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'isbn',
        'pengarang',
        'penerbit',
        'deskripsi',
        'cover',
        'tahun_terbit',
        'kategori_id',
        'jumlah',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function detailPeminjamans()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function peminjamans()
    {
        return $this->belongsToMany(Peminjaman::class, 'detail_peminjamans')
                    ->withPivot(['jumlah', 'status', 'tanggal_dikembalikan']);
    }
}
