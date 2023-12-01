<?php

return [
    'subdomain' => env('ZENDESK_SUBDOMAIN'), // 'yoursubdomain'

    'auth' => [
        'method' => env('ZENDESK_AUTHENTICATION_METHOD', 'token'), // 'basic' or 'token'
        'email_address' => env('ZENDESK_EMAIL_ADDRESS'), // Used for both authentication methods
        'password' => env('ZENDESK_PASSWORD'), // Only used if 'basic' is selected as authentication method
        'api_token' => env('ZENDESK_API_TOKEN'), // Only used if 'apitoken' is selected as authentication method
    ],
];
