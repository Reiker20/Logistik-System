<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUser',
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'id'       => ['type' => Type::nonNull(Type::int())],
            'name'     => ['type' => Type::string()],
            'nim'      => ['type' => Type::string()],
            'email'    => ['type' => Type::string()],
            'fakultas' => ['type' => Type::string()],
            'no_hp'    => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::find($args['id']);
        if (!$user) return null;
        $user->update($args);
        return $user;
    }
}
