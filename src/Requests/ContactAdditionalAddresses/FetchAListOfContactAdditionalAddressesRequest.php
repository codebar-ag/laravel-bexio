<?php

namespace CodebarAg\Bexio\Requests\ContactAdditionalAddresses;

use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\ContactAdditionalAddressDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfContactAdditionalAddressesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $contactId,
        readonly string $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/contact/'.$this->contactId.'/additional_address';
    }

    public function defaultQuery(): array
    {
        return [
            'orderBy' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $contacts = collect();

        foreach ($res as $contact) {
            $contacts->push(ContactAdditionalAddressDTO::fromArray($contact));
        }

        return $contacts;
    }
}
