<?php

namespace CodebarAg\Bexio\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Saloon\Http\Auth\AccessTokenAuthenticator;

/**
 * Stores OAuth2 authenticators in cache (encrypted).
 *
 * Tokens are encrypted using Laravel's Crypt facade before storage.
 */
class BexioOAuthTokenStore
{
    /**
     * The cache key for storing the authenticator.
     */
    protected string $cacheKey = 'bexio_oauth_authenticator';

    /**
     * Retrieve the authenticator from cache.
     */
    public function get(): ?AccessTokenAuthenticator
    {
        $encrypted = Cache::get($this->cacheKey);
        if (! $encrypted) {
            return null;
        }
        try {
            $serialized = Crypt::decrypt($encrypted);
        } catch (\Throwable $e) {
            // Could not decrypt, treat as cache miss
            return null;
        }

        return AccessTokenAuthenticator::unserialize($serialized);
    }

    /**
     * Store the authenticator in cache (encrypted).
     */
    public function put(AccessTokenAuthenticator $authenticator): void
    {
        $serialized = $authenticator->serialize();
        $encrypted = Crypt::encrypt($serialized);
        Cache::put($this->cacheKey, $encrypted);
    }

    /**
     * Remove the authenticator from cache.
     */
    public function forget(): void
    {
        Cache::forget($this->cacheKey);
    }
}
