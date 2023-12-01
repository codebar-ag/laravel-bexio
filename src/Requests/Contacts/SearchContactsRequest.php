<?php

namespace CodebarAg\Bexio\Requests\Contacts;

use CodebarAg\Bexio\Dto\ContactDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchContactsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly string $searchField,
        readonly string $searchTerm,
        readonly string $searchCriteria = '=',
        readonly string $order_by = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
        readonly bool $show_archived = false,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contact/search';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->order_by,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'show_archived' => $this->show_archived,
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
            $contacts->push(ContactDTO::fromArray($contact));
        }

        return $contacts;
    }
}
