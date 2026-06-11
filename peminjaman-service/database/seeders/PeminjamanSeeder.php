<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_pinjam'     => 'PJM-001',
                'user_id'         => 1,
                'item_id'         => 1,
                'jumlah'          => 1,
                'tanggal_pinjam'  => '2026-06-01',
                'tanggal_kembali' => '2026-06-07',
                'status'          => 'dipinjam',
            ],
            [
                'kode_pinjam'     => 'PJM-002',
                'user_id'         => 2,
                'item_id'         => 2,
                'jumlah'          => 2,
                'tanggal_pinjam'  => '2026-06-02',
                'tanggal_kembali' => '2026-06-08',
                'status'          => 'dikembalikan',
            ],
            [
                'kode_pinjam'     => 'PJM-003',
                'user_id'         => 3,
                'item_id'         => 3,
                'jumlah'          => 1,
                'tanggal_pinjam'  => '2026-06-03',
                'tanggal_kembali' => '2026-06-09',
                'status'          => 'dipinjam',
            ],
        ];

        foreach ($data as $item) {
            Peminjaman::create($item);
        }
    }
}