<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createUser',
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'name'     => ['type' => Type::nonNull(Type::string())],
            'nim'      => ['type' => Type::nonNull(Type::string())],
            'email'    => ['type' => Type::nonNull(Type::string())],
            'fakultas' => ['type' => Type::nonNull(Type::string())],
            'no_hp'    => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        return User::create($args);
    }
}
