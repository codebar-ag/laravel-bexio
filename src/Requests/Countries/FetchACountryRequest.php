<?php

namespace CodebarAg\Bexio\Requests\Countries;

use CodebarAg\Bexio\Dto\Countries\CountryDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchACountryRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $country_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/country/'.$this->country_id;
    }

    public function createDtoFromResponse(Response $response): CountryDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return CountryDTO::fromResponse($response);
    }
}
