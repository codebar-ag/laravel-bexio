<?php

namespace CodebarAg\Bexio\Dto\OAuthConfiguration;

use CodebarAg\Bexio\Enums\OAuthConfiguration\OAuthApiScope;
use CodebarAg\Bexio\Enums\OAuthConfiguration\OAuthOpenIDConnectScope;
use phpDocumentor\Reflection\Exception;

final class ConnectWithOAuth
{
    public string $client_id;

    public string $client_secret;

    public string $redirect_uri;

    public array $scopes;

    /**
     * @throws Exception
     */
    public function __construct(
        ?string $client_id = null,
        ?string $client_secret = null,
        ?string $redirect_uri = null,

        /**
         * @var array<string|OAuthOpenIDConnectScope|OAuthApiScope>|null
         */
        ?array $scopes = null
    ) {
        $this->client_id = $client_id ?? config('bexio.auth.oauth.client_id') ?? throw new Exception('Client ID is required.');
        $this->client_secret = $client_secret ?? config('bexio.auth.oauth.client_secret') ?? throw new Exception('Client secret is required.');
        $this->redirect_uri = $redirect_uri ?? config('bexio.auth.oauth.redirect_uri') ?? throw new Exception('Redirect URI is required.');

        $this->scopes = collect($scopes ?? config('bexio.auth.oauth.scopes', []))
            ->map(function (string|OAuthOpenIDConnectScope|OAuthApiScope $scope) {
                if ($scope instanceof OAuthOpenIDConnectScope || $scope instanceof OAuthApiScope) {
                    return $scope->value;
                }

                return $scope;
            })->toArray();
    }
}
