<?php

namespace CodebarAg\Bexio\Requests\ContactGroups;

use CodebarAg\Bexio\Dto\ContactGroups\ContactGroupDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAContactGroupRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contact_group/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ContactGroupDTO::fromResponse($response);
    }
}
