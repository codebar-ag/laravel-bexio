<?php

namespace CodebarAg\Bexio\Support;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationStoreResolver;
use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use DateTimeImmutable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use JsonException;
use Saloon\Http\Auth\AccessTokenAuthenticator;

class DefaultBexioOAuthAuthenticationStoreResolver implements BexioOAuthAuthenticationStoreResolver
{
    protected string $cacheKey = 'bexio_oauth_authenticator';

    public function get(): ?AccessTokenAuthenticator
    {
        $cacheStore = Cache::store(config('bexio.cache_store', config('cache.default')));

        if (! $cacheStore->has($this->cacheKey)) {
            return null;
        }

        try {
            $plain = Crypt::decrypt($cacheStore->get($this->cacheKey));

            $authenticator = $this->decodeStoredAuthenticator($plain);

            if ($authenticator->hasExpired()) {
                // We'll refresh the access token which will return a new authenticator
                // which we can store against our user in our application.

                $resolver = App::make(BexioOAuthConfigResolver::class);
                $connector = new BexioConnector($resolver->resolve(), autoResolveAndAuthenticate: false);

                $authenticator = $connector->refreshAccessToken($authenticator);

                $this->put($authenticator); // @phpstan-ignore-line
            }

            return $authenticator; // @phpstan-ignore-line
        } catch (\Throwable $e) {
            // Could not decrypt, treat as cache miss
            return null;
        }
    }

    public function put(AccessTokenAuthenticator $authenticator): void
    {
        $cacheStore = Cache::store(config('bexio.cache_store', config('cache.default')));

        $payload = $this->encodeAuthenticatorForStorage($authenticator);

        $encrypted = Crypt::encrypt($payload);

        $cacheStore->put($this->cacheKey, $encrypted);
    }

    public function forget(): void
    {
        $cacheStore = Cache::store(config('bexio.cache_store', config('cache.default')));

        $cacheStore->forget($this->cacheKey);
    }

    /**
     * @throws JsonException
     */
    protected function encodeAuthenticatorForStorage(AccessTokenAuthenticator $authenticator): string
    {
        return json_encode([
            'accessToken' => $authenticator->accessToken,
            'refreshToken' => $authenticator->refreshToken,
            'expiresAt' => $authenticator->expiresAt?->format(DATE_ATOM),
        ], JSON_THROW_ON_ERROR);
    }

    protected function decodeStoredAuthenticator(string $plain): AccessTokenAuthenticator
    {
        $trimmed = ltrim($plain);

        if ($trimmed !== '' && $trimmed[0] === '{') {
            $data = json_decode($plain, true, 512, JSON_THROW_ON_ERROR);
            $expiresAt = isset($data['expiresAt']) && is_string($data['expiresAt']) && $data['expiresAt'] !== ''
                ? new DateTimeImmutable($data['expiresAt'])
                : null;

            return new AccessTokenAuthenticator(
                $data['accessToken'],
                $data['refreshToken'] ?? null,
                $expiresAt,
            );
        }

        $legacy = unserialize($plain, [
            'allowed_classes' => [
                AccessTokenAuthenticator::class,
                DateTimeImmutable::class,
            ],
        ]);

        if (! $legacy instanceof AccessTokenAuthenticator) {
            throw new \InvalidArgumentException('Invalid stored Bexio OAuth authenticator.');
        }

        return $legacy;
    }
}
