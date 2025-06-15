<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\DefaultPositions\AddDefaultPositionDTO;
use CodebarAg\Bexio\Dto\DiscountPositions\AddDiscountPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\AddItemPositionDTO;
use CodebarAg\Bexio\Dto\PagebreakPositions\AddPagebreakPositionDTO;
use CodebarAg\Bexio\Dto\SubPositions\AddSubPositionDTO;
use CodebarAg\Bexio\Dto\SubtotalPositions\AddSubtotalPositionDTO;
use CodebarAg\Bexio\Dto\TextPositions\AddTextPositionDTO;
use CodebarAg\Bexio\Dto\Invoices\CreateInvoiceDTO;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAnInvoiceRequestNew extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public array|CreateInvoiceDTO $invoice,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice';
    }

    /**
     * Serialize the invoice for the request body.
     *
     * Backward compatible: Accepts both modern DTOs and legacy InvoicePositionDTOs/arrays in positions.
     * Converts legacy positions to the correct new DTOs based on 'type'.
     */
    protected function defaultBody(): array
    {
        $body = $this->invoice;
        if (! $body instanceof CreateInvoiceDTO) {
            $body = CreateInvoiceDTO::fromArray($body);
        }
        // Filter out all null values so Bexio does not see e.g. document_nr if null
        return array_filter($body->toArray(), fn($v) => $v !== null);
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
