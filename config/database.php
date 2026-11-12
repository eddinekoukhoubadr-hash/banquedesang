<?php

return [

    'default' => env('DB_CONNECTION', 'mongodb'),

    'connections' => [

        'mongodb' => [
            'driver' => 'mongodb',
            'dsn' => env('MONGO_URI'),
            'database' => env('DB_DATABASE', 'banque_de_sang'),
            ],

    ],

    'migrations' => 'migrations',

    'redis' => [
        'client' => 'phpredis',
    ],
];
