<?php

namespace CodebarAg\Bexio\Requests\Countries;

use CodebarAg\Bexio\Dto\Countries\CountryDTO;
use CodebarAg\Bexio\Enums\Countries\CountriesOrderByEnum;
use CodebarAg\Bexio\Enums\SearchCriteriaEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchCountriesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $searchField,
        public readonly string $searchTerm,
        public readonly string|SearchCriteriaEnum $searchCriteria = 'like',
        public readonly string|CountriesOrderByEnum $orderBy = 'id',
        public readonly int $limit = 100,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/country/search';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->orderBy instanceof CountriesOrderByEnum ? $this->orderBy->value : $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    protected function defaultBody(): array
    {
        return [
            [
                'field' => $this->searchField,
                'value' => $this->searchTerm,
                'criteria' => $this->searchCriteria instanceof SearchCriteriaEnum ? $this->searchCriteria->value : $this->searchCriteria,
            ],
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
