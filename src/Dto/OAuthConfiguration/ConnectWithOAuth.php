<?php

namespace CodebarAg\Bexio\Dto\OAuthConfiguration;

use CodebarAg\Bexio\Enums\OAuthConfiguration\OAuthApiScope;
use CodebarAg\Bexio\Enums\OAuthConfiguration\OAuthOpenIDConnectScope;

final class ConnectWithOAuth
{
    public string $client_id;

    public string $client_secret;

    public string $redirect_uri;

    public array $scopes;

    public function __construct(
        ?string $token = null,
        ?string $client_secret = null,
        ?string $redirect_uri = null,

        /**
         * @var array<string|OAuthOpenIDConnectScope|OAuthApiScope>|null
         */
        ?array $scopes = null
    ) {
        $this->client_id = $token ?? config('bexio.auth.oauth.client_id');
        $this->client_secret = $client_secret ?? config('bexio.auth.oauth.client_secret');
        $this->redirect_uri = $redirect_uri ?? config('bexio.auth.oauth.redirect_uri');

        $this->scopes = collect($scopes ?? config('bexio.auth.oauth.scopes', []))
            ->map(function (string|OAuthOpenIDConnectScope|OAuthApiScope $scope) {
                if ($scope instanceof OAuthOpenIDConnectScope || $scope instanceof OAuthApiScope) {
                    return $scope->value;
                }

                return $scope;
            })->toArray();
    }
}
