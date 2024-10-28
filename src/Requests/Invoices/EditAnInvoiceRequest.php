<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditAnInvoiceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $invoice_id,
        readonly ?InvoiceDTO $invoice,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id;
    }

    public function defaultBody(): array
    {
        if ($this->invoice !== null) {
            return $this->invoice->toArray();
        }

        return [];
    }

    public function createDtoFromResponse(Response $response): InvoiceDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        return InvoiceDTO::fromArray($res);
    }
}
