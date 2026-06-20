<?php

namespace App\GraphQL\Queries;

use App\Models\Notifikasi;
use Illuminate\Database\Eloquent\Collection;

final class DaftarNotifikasiQuery
{
    public function __invoke($rootValue, array $args): Collection
    {
        if (isset($args['id_pengguna'])) {
            return Notifikasi::where('id_pengguna', $args['id_pengguna'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return Notifikasi::orderBy('created_at', 'desc')->get();
    }
}
