<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_pinjam',
        'user_id',
        'item_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];
}