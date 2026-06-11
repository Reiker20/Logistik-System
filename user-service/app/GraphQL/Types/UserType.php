<?php

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'User',
        'description' => 'Data mahasiswa peminjam logistik',
        'model'       => User::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'ID user',
            ],
            'name' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Nama mahasiswa',
            ],
            'nim' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'NIM mahasiswa',
            ],
            'email' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Email mahasiswa',
            ],
            'fakultas' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Fakultas mahasiswa',
            ],
            'no_hp' => [
                'type'        => Type::string(),
                'description' => 'Nomor HP mahasiswa',
            ],
        ];
    }
}
