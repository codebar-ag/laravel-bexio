<?php

namespace CodebarAg\Bexio\Requests\BusinessYears;

use CodebarAg\Bexio\Dto\BusinessYears\BusinessYearDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfBusinessYearsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $limit = 2000,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/business_years';
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

        $businessYears = collect();

        foreach ($res as $businessYear) {
            $businessYears->push(BusinessYearDTO::fromArray($businessYear));
        }

        return $businessYears;
    }
}
