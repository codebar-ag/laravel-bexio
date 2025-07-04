<?php

namespace CodebarAg\Bexio\Support;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationStoreResolver;
use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
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
            $serialized = Crypt::decrypt($cacheStore->get($this->cacheKey));

            $authenticator = AccessTokenAuthenticator::unserialize($serialized);

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

        $serialized = $authenticator->serialize();

        $encrypted = Crypt::encrypt($serialized);

        $cacheStore->put($this->cacheKey, $encrypted);
    }

    public function forget(): void
    {
        $cacheStore = Cache::store(config('bexio.cache_store', config('cache.default')));

        $cacheStore->forget($this->cacheKey);
    }
}
