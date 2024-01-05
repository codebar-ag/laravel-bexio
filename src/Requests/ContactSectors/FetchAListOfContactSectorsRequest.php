<?php

namespace CodebarAg\Bexio\Requests\ContactSectors;

use CodebarAg\Bexio\Dto\ContactSectors\ContactSectorDTO;
use CodebarAg\Bexio\Enums\ContactSectors\OrderByEnum;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfContactSectorsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly string|OrderByEnum $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/contact_branch';
    }

    public function defaultQuery(): array
    {
        return [
            'orderBy' => $this->orderBy instanceof OrderByEnum ? $this->orderBy->value : $this->orderBy,
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
            $contacts->push(ContactSectorDTO::fromArray($contact));
        }

        return $contacts;
    }
}
