<?php

namespace CodebarAg\Bexio\Requests\Invoices\Payments;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteAPaymentRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $invoice_id,
        public readonly int $payment_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/payment/'.$this->payment_id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
