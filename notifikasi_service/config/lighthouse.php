<?php

return [
    'route' => [
        'uri' => '/graphql',
        'middleware' => [],
    ],
    'namespaces' => [
        'models' => ['App\\Models'],
        'queries' => 'App\\GraphQL\\Queries',
        'mutations' => 'App\\GraphQL\\Mutations',
    ],
    'schema_path' => base_path('graphql/schema.graphql'),
    'guard' => null,
];
