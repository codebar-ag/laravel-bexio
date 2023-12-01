<?php

return [
    'subdomain' => env('BEXIO_SUBDOMAIN'), // 'yoursubdomain'

    'auth' => [
        'method' => env('BEXIO_AUTHENTICATION_METHOD', 'token'), // 'basic' or 'token'
        'email_address' => env('BEXIO_EMAIL_ADDRESS'), // Used for both authentication methods
        'password' => env('BEXIO_PASSWORD'), // Only used if 'basic' is selected as authentication method
        'api_token' => env('BEXIO_API_TOKEN'), // Only used if 'apitoken' is selected as authentication method
    ],
];
