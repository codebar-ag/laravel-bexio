<?php

namespace CodebarAg\Bexio\Contracts;

use Saloon\Http\Auth\AccessTokenAuthenticator;

interface BexioOAuthAuthenticationStoreResolver
{
    public function get(): ?AccessTokenAuthenticator;

    public function put(AccessTokenAuthenticator $authenticator): void;

    public function forget(): void;
}
