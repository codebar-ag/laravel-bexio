<?php

namespace CodebarAg\Bexio\Requests\Invoices\Payments;

use CodebarAg\Bexio\Dto\Invoices\PaymentDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfPaymentsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $invoice_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/payment';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $payments = collect();

        foreach ($response->json() as $payment) {
            $payments->push(PaymentDTO::fromArray($payment));
        }

        return $payments;
    }
}
