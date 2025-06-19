<?php

namespace CodebarAg\Bexio;

use CodebarAg\Bexio\DTO\Config\ConfigWithCredentials;
use CodebarAg\Bexio\Services\BexioOAuthService;
use CodebarAg\Bexio\Support\BexioOAuthTokenStore;
use Illuminate\Support\Facades\Route;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\OAuth2\GetRefreshTokenRequest;
use Saloon\Http\PendingRequest;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class BexioConnector extends Connector
{
    use AlwaysThrowOnErrors, AuthorizationCodeGrant;

    public function __construct(
        protected readonly ?string $token = null,
        protected ?ConfigWithCredentials $configuration = null,
        protected ?BexioOAuthTokenStore $tokenStore = null,
        protected ?BexioOAuthService $bexioOAuthService = null,
    ) {
        if ($this->configuration === null) {
            if (app()->bound('bexio.config.resolver')) {
                $resolver = app('bexio.config.resolver');
                $this->configuration = $resolver(request());
            } else {
                $this->configuration = new ConfigWithCredentials();
            }
        }
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
     * Handles OAuth2 token refresh if needed.
     */
    public function boot(PendingRequest $pendingRequest): void
    {
        if (! config('bexio.auth.use_oauth2')) {
            return;
        }

        $pendingRequest->middleware()->onRequest(function (PendingRequest $pendingRequest) {
            // Prevent recursion: do not refresh while already refreshing
            if ($pendingRequest->getRequest() instanceof GetRefreshTokenRequest) {
                return;
            }

            $authenticator = $this->bexioOAuthService->getValidAuthenticator($this->tokenStore, $this, $this->configuration->identifier);

            if ($authenticator) {
                $pendingRequest->authenticate($authenticator);
            }
        });
    }

    protected function defaultAuth(): ?Authenticator
    {
        $token = $this->token ?? $this->configuration->token;
        if ($token) {
            return new TokenAuthenticator($token, 'Bearer');
        }

        $authenticator = $this->tokenStore->get($this->configuration->identifier ?? 'default');
        return $authenticator instanceof AccessTokenAuthenticator ? $authenticator : null;
    }

    /**
     * Saloon OAuth2 config for Bexio
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId($this->configuration->clientId)
            ->setClientSecret($this->configuration->clientSecret)
            ->setRedirectUri(Route::has('bexio.oauth.callback') ? route('bexio.oauth.callback') : null)
            ->setDefaultScopes(['openid', 'offline_access', 'email'])
            ->setAuthorizeEndpoint('https://auth.bexio.com/realms/bexio/protocol/openid-connect/auth')
            ->setTokenEndpoint('https://auth.bexio.com/realms/bexio/protocol/openid-connect/token');
    }
}
