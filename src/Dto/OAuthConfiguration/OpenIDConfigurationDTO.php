<?php

namespace CodebarAg\Bexio\Dto\OAuthConfiguration;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;

final class OpenIDConfigurationDTO
{
    public function __construct(
        public readonly string $issuer,
        public readonly string $authorizationEndpoint,
        public readonly string $tokenEndpoint,
        public readonly string $introspectionEndpoint,
        public readonly string $userinfoEndpoint,
        public readonly string $endSessionEndpoint,
    ) {}

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new \Exception('Failed to create DTO from Response');
        }

        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            issuer: Arr::get($data, 'issuer'),
            authorizationEndpoint: Arr::get($data, 'authorization_endpoint'),
            tokenEndpoint: Arr::get($data, 'token_endpoint'),
            introspectionEndpoint: Arr::get($data, 'introspection_endpoint'),
            userinfoEndpoint: Arr::get($data, 'userinfo_endpoint'),
            endSessionEndpoint: Arr::get($data, 'end_session_endpoint'),
        );
    }
}
