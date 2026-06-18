<?php

namespace CodebarAg\Bexio\Requests\Invoices\Payments;

use CodebarAg\Bexio\Dto\Invoices\PaymentDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAPaymentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $invoice_id,
        public readonly int $payment_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/payment/'.$this->payment_id;
    }

    public function createDtoFromResponse(Response $response): PaymentDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return PaymentDTO::fromArray($response->json());
    }
}
