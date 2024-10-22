<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class CancelAnInvoiceRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        readonly int $invoiceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoiceId.'/cancel';
    }

    /**
     * @throws \JsonException
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}