<?php

namespace CodebarAg\Bexio\Requests\OpenID;

use CodebarAg\Bexio\Dto\OAuthConfiguration\OpenIDConfigurationDTO;
use CodebarAg\Bexio\Dto\OpenID\UserInfoDTO;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchUserInfoRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        /** @var OpenIDConfigurationDTO $openIDConfiguration */
        $openIDConfiguration = (new OpenIDConfigurationRequest)->send()->dto();

        return $openIDConfiguration->userinfoEndpoint;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function createDtoFromResponse(Response $response): UserInfoDTO
    {
        if (! $response->successful()) {
            throw new \Exception('Request was not successful. Unable to create DTO.');
        }

        return UserInfoDTO::fromResponse($response);
    }
}
