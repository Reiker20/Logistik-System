<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'id_pengguna',
        'id_barang',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status_transaksi',
        'catatan',
    ];

    protected $casts = [
        'id_pengguna' => 'integer',
        'id_barang' => 'integer',
        'jumlah_pinjam' => 'integer',
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
