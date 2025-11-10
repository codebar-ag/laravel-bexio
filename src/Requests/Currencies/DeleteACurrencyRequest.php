<?php

namespace CodebarAg\Bexio\Requests\Currencies;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteACurrencyRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/currencies/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
