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
}
