<?php

return [

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'repairs' => [
            'driver' => 'local',
            'root' => storage_path('repairs'),
            'permissions' => [
                'file' => [
                    'public' => 0711,
                    'private' => 0711,
                ],
                'dir' => [
                    'public' => 0711,
                    'private' => 0711,
                ],
            ],
        ],

        'checkIn' => [
            'driver' => 'local',
            'root' => storage_path('checkIn'),
            'permissions' => [
                'file' => [
                    'public' => 0711,
                    'private' => 0711,
                ],
                'dir' => [
                    'public' => 0711,
                    'private' => 0711,
                ],
            ],
        ],
        'note' => [
            'driver' => 'local',
            'root' => storage_path('note'),
        ],
        'sign' => [
            'driver' => 'local', 
            'root' => storage_path('sign'),
            'permissions' => [
                'file' => [
                    'public' => 0711,
                    'private' => 0711,
                ],
                'dir' => [
                    'public' => 0711,
                    'private' => 0711,
                ],
            ],            
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
