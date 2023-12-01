<?php

namespace CodebarAg\Zendesk;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class ZendeskConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        if (! config('zendesk.subdomain')) {
            throw new \Exception('No subdomain provided.', 500);
        }

        return 'https://'.config('zendesk.subdomain').'.zendesk.com/api/v2';
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
        if (! config('zendesk.auth.method')) {
            throw new \Exception('No authentication method provided.', 500);
        }

        if (! config('zendesk.auth.email_address')) {
            throw new \Exception('No email address provided.', 500);
        }

        if (config('zendesk.auth.method') === 'basic' && ! config('zendesk.auth.password')) {
            throw new \Exception('No password provided for basic authentication.', 500);
        }

        if (config('zendesk.auth.method') === 'basic' && ! config('zendesk.auth.password')) {
            throw new \Exception('No password provided for basic authentication.', 500);
        }

        if (config('zendesk.auth.method') === 'token' && ! config('zendesk.auth.api_token')) {
            throw new \Exception('No API token provided for token authentication.', 500);
        }

        return match (config('zendesk.auth.method')) {
            'basic' => config('zendesk.auth.email_address').':'.config('zendesk.auth.password'),
            'token' => config('zendesk.auth.email_address').'/token:'.config('zendesk.auth.api_token'),
            default => throw new \Exception('Invalid authentication method provided.', 500),
        };
    }
}
