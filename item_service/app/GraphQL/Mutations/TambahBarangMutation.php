<?php

namespace App\GraphQL\Mutations;

use App\Models\Barang;

final class TambahBarangMutation
{
    public function __invoke($rootValue, array $args): Barang
    {
        return Barang::create($args);
    }
}
