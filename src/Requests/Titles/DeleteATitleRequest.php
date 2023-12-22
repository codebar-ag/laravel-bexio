<?php

namespace CodebarAg\Bexio\Requests\Titles;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteATitleRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/title/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
