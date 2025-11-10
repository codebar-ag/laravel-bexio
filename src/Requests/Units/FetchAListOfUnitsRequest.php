<?php

namespace CodebarAg\Bexio\Requests\Units;

use CodebarAg\Bexio\Dto\Units\UnitDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfUnitsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $limit = 500,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/unit';
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

        $units = collect();

        foreach ($res as $unit) {
            $units->push(UnitDTO::fromArray($unit));
        }

        return $units;
    }
}
