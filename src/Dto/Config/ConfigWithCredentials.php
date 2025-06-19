<?php

namespace CodebarAg\Bexio\DTO\Config;

use Saloon\Exceptions\OAuthConfigValidationException;

final class ConfigWithCredentials
{
    public readonly string $token;

    public readonly string $clientId;

    public readonly string $clientSecret;

    public readonly array $scopes;

    public readonly array $allowedEmails;

    public readonly bool $useOAuth2;

    public readonly string $cachePrefix;

    public readonly string $identifier;

    /**
     * Create a new Bexio configuration with credentials.
     *
     * @param  string|null  $clientId  The OAuth2 client ID
     * @param  string|null  $clientSecret  The OAuth2 client secret
     * @param  string|null  $token  The Personal Access Token (PAT) for PAT authentication
     * @param  array|null  $scopes  The OAuth2 scopes to request
     * @param  array|null  $allowedEmails  List of emails allowed to connect (optional)
     * @param  bool|null  $useOAuth2  Whether to use OAuth2 (true) or PAT (false)
     * @param  string|null  $cachePrefix  Cache key prefix for token storage
     *
     * @throws OAuthConfigValidationException If OAuth2 is enabled but credentials are missing
     */
    public function __construct(
        ?string $clientId = null,
        ?string $clientSecret = null,
        ?string $token = null,
        ?array $scopes = null,
        ?array $allowedEmails = null,
        ?bool $useOAuth2 = null,
        ?string $cachePrefix = null,
    ) {

        $this->useOAuth2 = filled($useOAuth2)
            ? $useOAuth2
            : config('bexio.auth.use_oauth2', false);

        $this->token = filled($token)
            ? $token
            : (config('bexio.auth.token') ?? '');

        $this->clientId = filled($clientId)
            ? $clientId
            : config('bexio.auth.oauth2.client_id') ?? '';

        $this->clientSecret = filled($clientSecret)
            ? $clientSecret
            : config('bexio.auth.oauth2.client_secret') ?? '';

        if ($this->useOAuth2 && (empty($this->clientId) || empty($this->clientSecret))) {
            throw new OAuthConfigValidationException(
                'Bexio OAuth2 credentials are required. Please provide clientId and clientSecret or set BEXIO_CLIENT_ID and BEXIO_CLIENT_SECRET in .env'
            );
        }

        $this->scopes = filled($scopes)
            ? $scopes
            : config('bexio.auth.oauth2.scopes', []);

        $this->allowedEmails = filled($allowedEmails)
            ? $allowedEmails
            : config('bexio.auth.oauth2.allowed_emails', []);

        $this->cachePrefix = filled($cachePrefix)
            ? $cachePrefix
            : config('bexio.cache_prefix', 'bexio_oauth_');

        $this->identifier = hash('sha256', "{$this->clientId}{$this->clientSecret}");
    }
}
