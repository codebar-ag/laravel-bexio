<?php

namespace CodebarAg\Bexio\Requests\Quotes;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeclineAQuoteRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $quote_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_offer/'.$this->quote_id.'/decline';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
