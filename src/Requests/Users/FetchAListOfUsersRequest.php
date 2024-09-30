<?php

namespace CodebarAg\Bexio\Requests\Users;

use CodebarAg\Bexio\Dto\Users\UserDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfUsersRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/users';
    }

    public function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $users = collect();

        foreach ($res as $user) {
            $users->push(UserDTO::fromArray($user));
        }

        return $users;
    }
}
