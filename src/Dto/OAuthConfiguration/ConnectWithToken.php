<?php

namespace CodebarAg\Bexio\Dto\OAuthConfiguration;

final class ConnectWithToken
{
    public string $token;

    public function __construct(
        ?string $token = null,
    ) {
        $this->token = $token ?? config('bexio.auth.token');
    }
}
