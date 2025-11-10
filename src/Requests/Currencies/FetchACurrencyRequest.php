<?php

namespace CodebarAg\Bexio\Requests\Currencies;

use CodebarAg\Bexio\Dto\Currencies\CurrencyDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchACurrencyRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/currencies/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): CurrencyDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return CurrencyDTO::fromResponse($response);
    }
}
