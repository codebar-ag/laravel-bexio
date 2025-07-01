<?php

namespace CodebarAg\Bexio;

use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\OAuthConfiguration\OpenIDConfigurationDTO;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
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
    ) {
        if (! $configuration) {
            // Resolve the resolver from Laravel's IoC container if no configuration is provided
            $resolver = App::make(BexioOAuthConfigResolver::class);
            $this->configuration = $resolver->resolve();
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

    protected function defaultAuth(): ?Authenticator
    {
        if ($this->configuration instanceof ConnectWithOAuth) {
            return null;
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
            ->setDefaultScopes($this->configuration->scopes)
            ->setRedirectUri($this->configuration->redirect_uri)
            ->setAuthorizeEndpoint($openIDConfiguration->authorizationEndpoint)
            ->setTokenEndpoint($openIDConfiguration->tokenEndpoint)
            ->setUserEndpoint($openIDConfiguration->userinfoEndpoint);
    }
}
