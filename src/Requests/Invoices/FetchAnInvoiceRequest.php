<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAnInvoiceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $invoice_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id;
    }

    public function createDtoFromResponse(Response $response): InvoiceDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $invoice = $response->json();

        return InvoiceDTO::fromArray($invoice);
    }
}
