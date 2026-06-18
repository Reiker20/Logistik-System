<?php

namespace App\GraphQL\Queries;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Collection;

final class DaftarPenggunaQuery
{
    public function __invoke($rootValue, array $args): Collection
    {
        return Pengguna::all();
    }
}
