<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status_pinjam',
        'catatan',
        'catatan_penolakan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function detailPeminjamans()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function bukus()
    {
        return $this->belongsToMany(Buku::class, 'detail_peminjamans')
                    ->withPivot(['jumlah', 'status', 'tanggal_dikembalikan']);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function scopePending($query)
    {
        return $query->where('status_pinjam', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_pinjam', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_pinjam', 'ditolak');
    }
}
