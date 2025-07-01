<?php

return [
    'auth' => [
        'token' => env('BEXIO_API_TOKEN'),

        'oauth' => [
            'client_id' => env('BEXIO_OAUTH_CLIENT_ID'),
            'client_secret' => env('BEXIO_OAUTH_CLIENT_SECRET'),
            'redirect_uri' => env('BEXIO_OAUTH_REDIRECT_URI'),
            'scopes' => explode(',', env('BEXIO_OAUTH_SCOPES')),
        ],
    ],

    'route_prefix' => null,
];
