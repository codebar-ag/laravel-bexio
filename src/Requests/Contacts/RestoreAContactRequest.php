<?php

namespace CodebarAg\Bexio\Requests\Contacts;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class RestoreAContactRequest extends Request
{
    protected Method $method = Method::PATCH;

    public function __construct(
        readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/contact/'.$this->id.'/restore';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
