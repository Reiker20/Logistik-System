<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Budi Santoso',
                'nim'      => '1234567890',
                'email'    => 'budi@student.ac.id',
                'fakultas' => 'Teknik Informatika',
                'no_hp'    => '081234567890',
            ],
            [
                'name'     => 'Siti Rahayu',
                'nim'      => '0987654321',
                'email'    => 'siti@student.ac.id',
                'fakultas' => 'Sistem Informasi',
                'no_hp'    => '089876543210',
            ],
            [
                'name'     => 'Ahmad Fauzi',
                'nim'      => '1122334455',
                'email'    => 'ahmad@student.ac.id',
                'fakultas' => 'Teknik Elektro',
                'no_hp'    => null,
            ],
            [
                'name'     => 'Dewi Lestari',
                'nim'      => '2233445566',
                'email'    => 'dewi@student.ac.id',
                'fakultas' => 'Manajemen Informatika',
                'no_hp'    => '082233445566',
            ],
            [
                'name'     => 'Rizky Pratama',
                'nim'      => '3344556677',
                'email'    => 'rizky@student.ac.id',
                'fakultas' => 'Teknik Informatika',
                'no_hp'    => '083344556677',
            ],
            [
                'name'     => 'Nurul Hidayah',
                'nim'      => '4455667788',
                'email'    => 'nurul@student.ac.id',
                'fakultas' => 'Sistem Informasi',
                'no_hp'    => '084455667788',
            ],
            [
                'name'     => 'Fajar Ramadhan',
                'nim'      => '5566778899',
                'email'    => 'fajar@student.ac.id',
                'fakultas' => 'Teknik Komputer',
                'no_hp'    => null,
            ],
            [
                'name'     => 'Anisa Putri',
                'nim'      => '6677889900',
                'email'    => 'anisa@student.ac.id',
                'fakultas' => 'Teknik Informatika',
                'no_hp'    => '086677889900',
            ],
            [
                'name'     => 'Dimas Ardiansyah',
                'nim'      => '7788990011',
                'email'    => 'dimas@student.ac.id',
                'fakultas' => 'Teknik Elektro',
                'no_hp'    => '087788990011',
            ],
            [
                'name'     => 'Mega Silviana',
                'nim'      => '8899001122',
                'email'    => 'mega@student.ac.id',
                'fakultas' => 'Manajemen Informatika',
                'no_hp'    => '088899001122',
            ],
        ];

        foreach ($users as $data) {
            User::create($data);
        }
    }
}
