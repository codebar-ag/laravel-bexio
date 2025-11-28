<?php

namespace CodebarAg\Bexio\Requests\Quotes;

use CodebarAg\Bexio\Dto\Quotes\QuoteDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAQuoteRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $quote_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_offer/'.$this->quote_id;
    }

    public function createDtoFromResponse(Response $response): QuoteDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $quote = $response->json();

        return QuoteDTO::fromArray($quote);
    }
}
