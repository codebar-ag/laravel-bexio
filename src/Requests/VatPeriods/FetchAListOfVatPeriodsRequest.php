<?php

namespace CodebarAg\Bexio\Requests\VatPeriods;

use CodebarAg\Bexio\Dto\VatPeriods\VatPeriodDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfVatPeriodsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $limit = 2000,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/vat_periods';
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

        return collect($res)->map(function ($currency) {
            return VatPeriodDTO::fromArray($currency);
        });
    }
}
