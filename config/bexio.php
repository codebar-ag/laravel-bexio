<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Prefix
    |--------------------------------------------------------------------------
    | Prefix used for all Bexio OAuth2 cache entries (token storage, etc).
    | Change this if you want to avoid collisions in a multi-app environment.
    */
    'cache_prefix' => env('BEXIO_CACHE_PREFIX', 'bexio_oauth_'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    | Configure which authentication method to use (PAT or OAuth2).
    | Only one method should be enabled at a time.
    */
    'auth' => [

        /*
        |--------------------------------------------------------------------------
        | Use OAuth2
        |--------------------------------------------------------------------------
        | Set to true to enable OAuth2 Authorization Code Grant.
        | If false or unset, Personal Access Token (PAT) authentication will be used.
        */
        'use_oauth2' => env('BEXIO_USE_OAUTH2', false),

        /*
        |--------------------------------------------------------------------------
        | Personal Access Token (PAT)
        |--------------------------------------------------------------------------
        | Used for legacy authentication. use_oauth2 must be false or unset.
        */
        'token' => env('BEXIO_API_TOKEN'),

        /*
        |--------------------------------------------------------------------------
        | OAuth2 Settings
        |--------------------------------------------------------------------------
        | Settings for OAuth2 Authorization Code Grant.
        */
        'oauth2' => [
            'client_id' => env('BEXIO_CLIENT_ID'),
            'client_secret' => env('BEXIO_CLIENT_SECRET'),

            /*
            |--------------------------------------------------------------------------
            | Allowed Emails
            |--------------------------------------------------------------------------
            | Comma-separated list of emails allowed to authenticate.
            | Example: 'user1@example.com,user2@example.com'
            */
            'allowed_emails' => array_filter(array_map('trim', explode(',', env('BEXIO_ALLOWED_EMAILS', '')))),

            /*
            |--------------------------------------------------------------------------
            | OAuth2 Scopes
            |--------------------------------------------------------------------------
            | Specify the scopes to request from Bexio.
            | Example: ['openid', 'profile', 'email']
            */
            'scopes' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    | Prefix for all Bexio package routes (default: 'bexio').
    | Change this if you want to customize the route group.
    */
    'route_prefix' => 'bexio',
];
