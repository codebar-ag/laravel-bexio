<?php

namespace CodebarAg\Bexio\Requests\Countries;

use CodebarAg\Bexio\Dto\Countries\CountryDTO;
use CodebarAg\Bexio\Enums\Countries\CountriesOrderByEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfCountriesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly string|CountriesOrderByEnum $orderBy = 'id',
        public readonly int $limit = 100,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/country';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->orderBy instanceof CountriesOrderByEnum ? $this->orderBy->value : $this->orderBy,
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

        $countries = collect();

        foreach ($res as $country) {
            $countries->push(CountryDTO::fromArray($country));
        }

        return $countries;
    }
}
