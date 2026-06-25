<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'kategori',
        'jumlah_tersedia',
        'satuan',
        'lokasi_penyimpanan',
        'deskripsi',
    ];

    protected $casts = [
        'jumlah_tersedia' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
