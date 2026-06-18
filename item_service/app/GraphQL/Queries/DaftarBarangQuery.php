<?php

namespace App\GraphQL\Queries;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Collection;

final class DaftarBarangQuery
{
    public function __invoke($rootValue, array $args): Collection
    {
        if (isset($args['kategori'])) {
            return Barang::where('kategori', $args['kategori'])->get();
        }

        return Barang::all();
    }
}
