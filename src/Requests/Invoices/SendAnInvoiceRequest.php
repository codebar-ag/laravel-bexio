<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use Exception;
use CodebarAg\Bexio\Dto\Invoices\InvoiceSentDTO;
use CodebarAg\Bexio\Dto\Invoices\SendInvoiceDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Contracts\Body\HasBody;

/**
 * Send an invoice by email
 * POST /2.0/kb_invoice/{invoice_id}/send
 */
class SendAnInvoiceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $invoice_id,
        readonly array|SendInvoiceDTO $invoice,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/kb_invoice/{$this->invoice_id}/send";
    }

    protected function defaultBody(): array
    {
        $body = $this->invoice;
        if (! $body instanceof SendInvoiceDTO) {
            $body = SendInvoiceDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
