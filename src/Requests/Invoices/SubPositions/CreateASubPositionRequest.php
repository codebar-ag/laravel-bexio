<?php

namespace CodebarAg\Bexio\Requests\Invoices\SubPositions;

use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\Abstractions\InvoicePositionDTO as NewInvoicePositionDTO;
use Exception;
use Illuminate\Support\Collection;
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
        protected InvoicePositionDTO|NewInvoicePositionDTO|null $position = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf('/2.0/%s/%s/kb_position_subposition', $this->kb_document_type, $this->invoice_id);
    }

    public function defaultBody(): array
    {
        if ($this->position) {
            if ($this->position->type !== 'KbSubPosition') {
                throw new Exception('Position must be of type KbSubPosition');
            }

            return $this->filterPosition($this->position)->toArray();
        }

        return [];
    }

    protected function filterPosition(InvoicePositionDTO|NewInvoicePositionDTO $position): Collection
    {
        return collect($position->toArray())->only([
            'text',
            'show_pos_nr',
        ]);
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
