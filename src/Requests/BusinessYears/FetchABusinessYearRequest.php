<?php

namespace CodebarAg\Bexio\Requests\BusinessYears;

use CodebarAg\Bexio\Dto\BusinessYears\BusinessYearDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchABusinessYearRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/business_years/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return BusinessYearDTO::fromResponse($response);
    }
}
