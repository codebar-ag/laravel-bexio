<?php

namespace CodebarAg\Bexio\Requests\Invoices\DefaultPositions;

use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateASubPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $kb_document_type,
        protected int $invoice_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf('/2.0/%s/%s/kb_position_subposition', $this->kb_document_type, $this->invoice_id);
    }

    public function createDtoFromResponse(Response $response): InvoicePositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        return InvoicePositionDTO::fromArray($res);
    }
}
