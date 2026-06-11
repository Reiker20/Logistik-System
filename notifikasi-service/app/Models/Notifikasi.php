<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'kode_pinjam',
        'user_id',
        'item_id',
        'pesan',
        'jenis',
        'status',
    ];
}
