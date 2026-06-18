<?php

namespace App\GraphQL\Mutations;

use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

final class TambahPenggunaMutation
{
    public function __invoke($rootValue, array $args): Pengguna
    {
        $args['kata_sandi'] = Hash::make($args['kata_sandi']);

        return Pengguna::create($args);
    }
}
