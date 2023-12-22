<?php

namespace CodebarAg\Bexio\Requests\Titles;

use CodebarAg\Bexio\Dto\Titles\TitleDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfTitlesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/title';
    }

    public function defaultQuery(): array
    {
        return [
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

        $addresses = collect();

        foreach ($res as $address) {
            $addresses->push(TitleDTO::fromArray($address));
        }

        return $addresses;
    }
}
