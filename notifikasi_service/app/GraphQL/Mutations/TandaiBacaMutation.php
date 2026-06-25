<?php

namespace App\GraphQL\Mutations;

use App\Models\Notifikasi;

final class TandaiBacaMutation
{
    public function __invoke($rootValue, array $args): ?Notifikasi
    {
        $notifikasi = Notifikasi::find($args['id']);

        if (!$notifikasi) {
            return null;
        }

        $notifikasi->status_baca = $args['status_baca'] ?? true;
        $notifikasi->save();

        return $notifikasi;
    }
}
