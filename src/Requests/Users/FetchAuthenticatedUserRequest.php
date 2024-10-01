<?php

namespace CodebarAg\Bexio\Requests\Users;

use CodebarAg\Bexio\Dto\Users\UserDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAuthenticatedUserRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/3.0/users/me';
    }

    public function createDtoFromResponse(Response $response): UserDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return UserDTO::fromArray($response->json());
    }
}
