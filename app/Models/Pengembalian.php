<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengajuan',
        'tanggal_dikembalikan',
        'status',
        'catatan_penolakan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function anggota()
    {
        return $this->hasOneThrough(Anggota::class, Peminjaman::class, 'id', 'id', 'peminjaman_id', 'anggota_id');
    }

    public function detailPeminjamans()
    {
        return $this->hasManyThrough(DetailPeminjaman::class, Peminjaman::class, 'id', 'peminjaman_id', 'peminjaman_id', 'id');
    }
}
