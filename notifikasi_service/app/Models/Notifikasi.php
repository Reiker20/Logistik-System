<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'id_pengguna',
        'id_peminjaman',
        'judul',
        'isi_pesan',
        'jenis_notifikasi',
        'status_baca',
    ];

    protected $casts = [
        'id_pengguna' => 'integer',
        'id_peminjaman' => 'integer',
        'status_baca' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
