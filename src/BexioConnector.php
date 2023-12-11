<?php

namespace CodebarAg\Bexio;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class BexioConnector extends Connector
{
    public function __construct(
        protected readonly ?string $token = null,
    ) {
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.bexio.com';
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
        return new TokenAuthenticator($this->token ?? config('bexio.auth.token'), 'Bearer');
    }
}
