<?php

namespace CodebarAg\Bexio\Requests\OAuth;

use CodebarAg\Bexio\Dto\OAuthConfiguration\OpenIDConfigurationDTO;
use Exception;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Http\SoloRequest;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;


class OpenIDConfigurationRequest extends SoloRequest implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function __construct(
    ) {}

    public function resolveEndpoint(): string
    {
        return 'https://auth.bexio.com/realms/bexio/.well-known/openid-configuration';
    }
    public function createDtoFromResponse(Response $response): OpenIDConfigurationDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to fetch openid information.');
        }

        return OpenIDConfigurationDTO::fromResponse($response);
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store(config('cache.default')));
    }

    public function cacheExpiryInSeconds(): int
    {
        return 60 * 60 * 24; // 1 day
    }

}
