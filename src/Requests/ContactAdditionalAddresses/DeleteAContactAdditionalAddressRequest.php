<?php

namespace CodebarAg\Bexio\Requests\ContactAdditionalAddresses;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteAContactAdditionalAddressRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        readonly int $contactId,
        readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contact/'.$this->contactId.'/additional_address/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
