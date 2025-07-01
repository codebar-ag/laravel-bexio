<?php

namespace CodebarAg\Bexio\Contracts;

use Saloon\Http\Auth\AccessTokenAuthenticator;

interface BexioOAuthAuthenticatonStoreResolver
{
    public function get(): ?AccessTokenAuthenticator;

    public function put(AccessTokenAuthenticator $authenticator): void;

    public function forget(): void;
}
