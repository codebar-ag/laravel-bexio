<?php

return [
    'auth' => [
        'use_oauth2' => env('BEXIO_USE_OAUTH2', false),
        'token' => env('BEXIO_API_TOKEN'),
        'client_id' => env('BEXIO_OAUTH2_CLIENT_ID'),
        'client_secret' => env('BEXIO_OAUTH2_CLIENT_SECRET'),
        'oauth_email' => env('BEXIO_OAUTH2_EMAIL'),
        'scopes' => [],
    ],

    /*	'auth' => [
            'token' => env('BEXIO_API_TOKEN'),

            'oauth2' => [
                'client_id' => env('BEXIO_OAUTH2_CLIENT_ID'),
                'client_secret' => env('BEXIO_OAUTH2_CLIENT_SECRET'),
                'email' => env('BEXIO_OAUTH2_EMAIL'),
                'scopes' => [],
            ],*/

    'route_prefix' => 'bexio',
];
