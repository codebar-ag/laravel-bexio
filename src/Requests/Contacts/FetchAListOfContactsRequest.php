<?php

namespace CodebarAg\Bexio\Requests\Contacts;

use CodebarAg\Bexio\Dto\Contacts\ContactDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfContactsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly string $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
        readonly bool $show_archived = false,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contact';
    }

    public function defaultQuery(): array
    {
        return [
            'orderBy' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'show_archived' => $this->show_archived,
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
