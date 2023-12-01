<?php

namespace CodebarAg\Bexio;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class BexioConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        if (! config('bexio.subdomain')) {
            throw new \Exception('No subdomain provided.', 500);
        }

        return 'https://'.config('bexio.subdomain').'.bexio.com/api/v2';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultAuth(): ?Authenticator
    {
        $authenticationString = $this->setAuth();

        return new TokenAuthenticator(base64_encode($authenticationString), 'Basic');
    }

    public function setAuth(): string
    {
        if (! config('bexio.auth.method')) {
            throw new \Exception('No authentication method provided.', 500);
        }

        if (! config('bexio.auth.email_address')) {
            throw new \Exception('No email address provided.', 500);
        }

        if (config('bexio.auth.method') === 'basic' && ! config('bexio.auth.password')) {
            throw new \Exception('No password provided for basic authentication.', 500);
        }

        if (config('bexio.auth.method') === 'basic' && ! config('bexio.auth.password')) {
            throw new \Exception('No password provided for basic authentication.', 500);
        }

        if (config('bexio.auth.method') === 'token' && ! config('bexio.auth.api_token')) {
            throw new \Exception('No API token provided for token authentication.', 500);
        }

        return match (config('bexio.auth.method')) {
            'basic' => config('bexio.auth.email_address').':'.config('bexio.auth.password'),
            'token' => config('bexio.auth.email_address').'/token:'.config('bexio.auth.api_token'),
            default => throw new \Exception('Invalid authentication method provided.', 500),
        };
    }
}
