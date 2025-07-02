<?php

namespace CodebarAg\Bexio;

use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationStoreResolver;
use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\OAuthConfiguration\OpenIDConfigurationDTO;
use CodebarAg\Bexio\Enums\OAuthConfiguration\OAuthOpenIDConnectScope;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use Exception;
use Illuminate\Support\Facades\App;
use Saloon\Contracts\Authenticator;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;

class BexioConnector extends Connector
{
    use AuthorizationCodeGrant;

    public function __construct(
        protected null|ConnectWithToken|ConnectWithOAuth $configuration = null,
        protected bool $autoResolveAndAuthenticate = true,
    ) {
        if ($this->autoResolveAndAuthenticate) {
            // Resolve the resolver from Laravel's IoC container if no configuration is provided
            if (! $this->configuration) {
                $this->configuration = App::make(BexioOAuthConfigResolver::class)->resolve();
            }

            // If the configuration is an instance of ConnectWithOAuth, we try to authenticate so the developer doesn't have to do it.
            if ($this->configuration instanceof ConnectWithOAuth) {
                if ($authenticator = App::make(BexioOAuthAuthenticationStoreResolver::class)->get()) {
                    $this->authenticate($authenticator);
                }
            }
        }
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
     * @throws Exception
     */
    protected function defaultAuth(): ?Authenticator
    {
        if ($this->configuration instanceof ConnectWithOAuth) {
            return null;
        }

        if (! $this->configuration instanceof ConnectWithToken) {
            throw new Exception('Trying to authenticate without ConnectWithOAuth or ConnectWithToken');
        }

        return new TokenAuthenticator($this->configuration->token, 'Bearer');
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        /** @var OpenIDConfigurationDTO $openIDConfiguration */
        $openIDConfiguration = (new OpenIDConfigurationRequest)->send()->dto();

        return OAuthConfig::make()
            ->setClientId($this->configuration->client_id)
            ->setClientSecret($this->configuration->client_secret)
            ->setDefaultScopes([
                OAuthOpenIDConnectScope::OPENID->value,
                OAuthOpenIDConnectScope::PROFILE->value,
                OAuthOpenIDConnectScope::EMAIL->value,
            ])
            ->setRedirectUri($this->configuration->redirect_uri)
            ->setAuthorizeEndpoint($openIDConfiguration->authorizationEndpoint)
            ->setTokenEndpoint($openIDConfiguration->tokenEndpoint)
            ->setUserEndpoint($openIDConfiguration->userinfoEndpoint);
    }
}
