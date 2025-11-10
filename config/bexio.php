<?php

return [
    'auth' => [
        'token' => env('BEXIO_API_TOKEN'),

        'oauth' => [
            'client_id' => env('BEXIO_OAUTH_CLIENT_ID'),
            'client_secret' => env('BEXIO_OAUTH_CLIENT_SECRET'),

            'scopes' => array_filter(
                array_map('trim', explode(',', (string) env('BEXIO_OAUTH_SCOPES', '')))
            ),
        ],
    ],

    // Cache store (falls back to the default cache store if not set)
    'cache_store' => env('BEXIO_CACHE_STORE'),

    // The prefix for the authentication routes
    'route_prefix' => null,

    // Middleware for the authentication routes 'web' is included by default.
    // You can add your own middleware here, e.g. ['auth', 'verified']
    'route_middleware' => [],

    // Redirect URL after OAuth callback
    'redirect_url' => env('BEXIO_REDIRECT_URL', ''),
];
