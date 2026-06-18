<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CopyAnInvoiceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $invoice_id,
        public readonly int $contact_id,
        public readonly ?int $contact_sub_id = null,
        public readonly ?string $is_valid_from = null,
        public readonly ?string $title = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/copy';
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'contact_id' => $this->contact_id,
            'contact_sub_id' => $this->contact_sub_id,
            'is_valid_from' => $this->is_valid_from,
            'title' => $this->title,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): InvoiceDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return InvoiceDTO::fromArray($response->json());
    }
}
