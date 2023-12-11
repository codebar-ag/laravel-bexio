<?php

namespace CodebarAg\Bexio\Requests\ContactAdditionalAddresses;

use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\ContactAdditionalAddressDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchContactAdditionalAddressesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $contactId,
        readonly string $searchField,
        readonly string $searchTerm,
        readonly string $searchCriteria = '=',
        readonly string $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/contact/'.$this->contactId.'/additional_address/search';
    }

    public function defaultQuery(): array
    {
        return [
            'orderBy' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'query' => [
                'field' => $this->searchField,
                'value' => $this->searchTerm,
                'criteria' => $this->searchCriteria,
            ],
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
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
