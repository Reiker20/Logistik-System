<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'nama_barang' => 'Proyektor Epson',
                'kategori'    => 'Elektronik',
                'stok'        => 5,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Proyektor untuk presentasi',
            ],
            [
                'nama_barang' => 'Kamera Canon DSLR',
                'kategori'    => 'Elektronik',
                'stok'        => 3,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Kamera untuk dokumentasi acara',
            ],
            [
                'nama_barang' => 'Tripod Kamera',
                'kategori'    => 'Aksesoris',
                'stok'        => 8,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Tripod untuk kamera DSLR',
            ],
            [
                'nama_barang' => 'Microphone Wireless',
                'kategori'    => 'Audio',
                'stok'        => 4,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Microphone untuk presentasi dan acara',
            ],
            [
                'nama_barang' => 'Laptop Lenovo',
                'kategori'    => 'Elektronik',
                'stok'        => 2,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Laptop untuk kebutuhan presentasi',
            ],
            [
                'nama_barang' => 'Layar Proyektor',
                'kategori'    => 'Perlengkapan',
                'stok'        => 6,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Layar lipat untuk proyektor',
            ],
            [
                'nama_barang' => 'Extension Kabel',
                'kategori'    => 'Perlengkapan',
                'stok'        => 10,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Kabel perpanjangan 5 meter',
            ],
            [
                'nama_barang' => 'Speaker Portable',
                'kategori'    => 'Audio',
                'stok'        => 3,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Speaker bluetooth portable',
            ],
            [
                'nama_barang' => 'Whiteboard Portable',
                'kategori'    => 'Perlengkapan',
                'stok'        => 4,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Whiteboard lipat untuk presentasi',
            ],
            [
                'nama_barang' => 'Drone DJI Mini',
                'kategori'    => 'Elektronik',
                'stok'        => 1,
                'kondisi'     => 'baik',
                'deskripsi'   => 'Drone untuk dokumentasi udara',
            ],
        ];

        foreach ($items as $data) {
            Item::create($data);
        }
    }
}