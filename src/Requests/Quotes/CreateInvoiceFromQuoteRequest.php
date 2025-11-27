<?php

namespace CodebarAg\Bexio\Requests\Quotes;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class CreateInvoiceFromQuoteRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $quote_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_offer/'.$this->quote_id.'/kb_invoice';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
