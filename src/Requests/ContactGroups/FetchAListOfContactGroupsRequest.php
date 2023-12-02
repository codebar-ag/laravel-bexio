<?php

namespace CodebarAg\Bexio\Requests\ContactGroups;

use CodebarAg\Bexio\Dto\ContactGroups\ContactGroupDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfContactGroupsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly string $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contact_group';
    }

    public function defaultQuery(): array
    {
        return [
            'orderBy' => $this->orderBy,
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

        $contacts = collect();

        foreach ($res as $contact) {
            $contacts->push(ContactGroupDTO::fromArray($contact));
        }

        return $contacts;
    }
}
