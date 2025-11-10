<?php

namespace CodebarAg\Bexio\Requests\Taxes;

use CodebarAg\Bexio\Dto\Taxes\TaxDTO;
use CodebarAg\Bexio\Enums\Taxes\ScopeEnum;
use CodebarAg\Bexio\Enums\Taxes\TypesEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfTaxesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $limit = 2000,
        public readonly int $offset = 0,
        public readonly null|string|ScopeEnum $scope = null,
        public readonly ?string $date = null,
        public readonly null|string|TypesEnum $types = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/taxes';
    }

    public function defaultQuery(): array
    {
        $query = [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];

        if ($this->scope) {
            $query['scope'] = $this->scope instanceof ScopeEnum ? $this->scope->value : $this->scope;
        }

        if ($this->date) {
            $query['date'] = $this->date;
        }

        if ($this->types) {
            $query['types'] = $this->types instanceof TypesEnum ? $this->types->value : $this->types;
        }

        return $query;
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $taxes = collect();

        foreach ($res as $currency) {
            $taxes->push(TaxDTO::fromArray($currency));
        }

        return $taxes;
    }
}
