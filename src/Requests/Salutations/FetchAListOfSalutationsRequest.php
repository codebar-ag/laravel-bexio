<?php

namespace CodebarAg\Bexio\Requests\Salutations;

use CodebarAg\Bexio\Dto\Salutations\SalutationDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfSalutationsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $limit = 500,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/salutation';
    }

    public function defaultQuery(): array
    {
        return [
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

        $addresses = collect();

        foreach ($res as $address) {
            $addresses->push(SalutationDTO::fromArray($address));
        }

        return $addresses;
    }
}
