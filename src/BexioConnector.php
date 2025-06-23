<?php

namespace CodebarAg\Bexio;

use CodebarAg\Bexio\Services\BexioOAuthService;
use CodebarAg\Bexio\Support\BexioOAuthTokenStore;
use Saloon\Contracts\Authenticator;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\OAuth2\GetRefreshTokenRequest;
use Saloon\Http\PendingRequest;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class BexioConnector extends Connector
{
    use AlwaysThrowOnErrors, AuthorizationCodeGrant;

    public function __construct(
        protected readonly ?string $token = null,
        protected ?BexioOAuthTokenStore $tokenStore = null,
        protected ?BexioOAuthService $bexioOAuthService = null,
    ) {
        $this->tokenStore ??= app(BexioOAuthTokenStore::class);
        $this->bexioOAuthService ??= app(BexioOAuthService::class);
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.bexio.com';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    /**
     * Saloon boot method: runs before every request.
     * Handles token refresh for OAuth2 and sets PAT for legacy tokens.
     */
    public function boot(PendingRequest $pendingRequest): void
    {
        $pendingRequest->middleware()->onRequest(function (PendingRequest $pendingRequest) {
            if (config('bexio.auth.use_oauth2')) {
                // Prevent recursion: do not refresh while already refreshing
                if ($pendingRequest->getRequest() instanceof GetRefreshTokenRequest) {
                    return;
                }
                $authenticator = $this->tokenStore->get();
                if ($authenticator && $authenticator->hasExpired()) {
                    $authenticator = $this->bexioOAuthService->refreshAuthenticator($this->tokenStore, $this);
                    $pendingRequest->authenticate($authenticator);
                }
            }
        });
    }

    protected function defaultAuth(): ?Authenticator
    {
        if (config('bexio.auth.use_oauth2')) {
            return $this->tokenStore->get();
        }

        return new TokenAuthenticator($this->token ?? config('bexio.auth.token'), 'Bearer');
    }

    /**
     * Saloon OAuth2 config for Bexio
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId(config('bexio.auth.client_id'))
            ->setClientSecret(config('bexio.auth.client_secret'))
            ->setDefaultScopes(['openid', 'offline_access', 'email'])
            ->setRedirectUri(route('bexio.oauth.callback'))
            ->setAuthorizeEndpoint('https://auth.bexio.com/realms/bexio/protocol/openid-connect/auth')
            ->setTokenEndpoint('https://auth.bexio.com/realms/bexio/protocol/openid-connect/token');
    }
}
