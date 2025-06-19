<?php

namespace CodebarAg\Bexio\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Saloon\Http\Auth\AccessTokenAuthenticator;

class BexioOAuthTokenStore
{
    private string $prefix;

    public function __construct(?string $prefix = null)
    {
        $this->prefix = $prefix ?? config('bexio.storage.cache.prefix', 'bexio_oauth_');
    }

    public function get(?string $identifier = null): ?AccessTokenAuthenticator
    {
        $encrypted = Cache::get($this->getCacheKey($identifier));
        if (! $encrypted) {
            return null;
        }

        try {
            $serialized = Crypt::decrypt($encrypted);
        } catch (\Throwable $e) {
            return null;
        }

        return AccessTokenAuthenticator::unserialize($serialized);
    }

    public function put(AccessTokenAuthenticator $authenticator, ?string $identifier = null): void
    {
        $serialized = $authenticator->serialize();
        $encrypted = Crypt::encrypt($serialized);
        Cache::put($this->getCacheKey($identifier), $encrypted);
    }

    public function forget(?string $identifier = null): void
    {
        Cache::forget($this->getCacheKey($identifier));
    }

    private function getCacheKey(?string $identifier): string
    {
        return $identifier
            ? "{$this->prefix}{$identifier}"
            : "{$this->prefix}default";
    }
}
