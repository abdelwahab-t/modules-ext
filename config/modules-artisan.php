<?php
return [
    'module_path' => 'app/Modules',

    'base_namespace' => 'App\\Modules',

    'type_map' => [
        'model' => 'App/Models',
        'migration' => 'Database/Migrations',
        'controller' => 'App/Http/Controllers',
        'request' => 'App/Http/Requests',
        'resource' => 'App/Http/Resources',
        'factory' => 'Database/Factories',
        'seeder' => 'Database/Seeders',
        'policy' => 'App/Policies',
        'test' => 'Tests/Feature',
        'mail' => 'App/Mail',
        'event' => 'App/Events',
        'listener' => 'App/Listeners',
        'job' => 'App/Jobs',
        'broadcast' => 'App/Broadcasting',
    ],
];
