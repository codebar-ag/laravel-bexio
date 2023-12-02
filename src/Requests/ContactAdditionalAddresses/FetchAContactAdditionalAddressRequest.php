<?php

namespace CodebarAg\Bexio\Requests\ContactAdditionalAddresses;

use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\ContactAdditionalAddressDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAContactAdditionalAddressRequest extends Request
{
    protected Method $method = Method::GET;

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

        return ContactAdditionalAddressDTO::fromResponse($response);
    }
}
