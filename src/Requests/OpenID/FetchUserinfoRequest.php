<?php

namespace CodebarAg\Bexio\Requests\OpenID;

use CodebarAg\Bexio\Dto\OpenID\UserinfoDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * FetchUserinfoRequest
 *
 * Example usage:
 * $response = $connector->send(new FetchUserinfoRequest());
 * $userinfo = FetchUserinfoRequest::mapToDTO($response);
 */
class FetchUserinfoRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'https://auth.bexio.com/realms/bexio/protocol/openid-connect/userinfo';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    /**
     * Instance method for DTO mapping (for consistency with other requests)
     */
    public function createDtoFromResponse(Response $response): UserinfoDTO
    {
        if (! $response->successful()) {
            throw new \Exception('Request was not successful. Unable to create DTO.');
        }

        return UserinfoDTO::fromResponse($response);
    }
}
