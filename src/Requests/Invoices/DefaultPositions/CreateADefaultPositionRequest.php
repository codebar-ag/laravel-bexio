<?php

namespace CodebarAg\Bexio\Requests\Invoices\DefaultPositions;

use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateADefaultPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $kb_document_type,
        protected int $invoice_id,
        protected ?InvoicePositionDTO $position = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf('/2.0/%s/%s/kb_position_custom', $this->kb_document_type, $this->invoice_id);
    }

    public function defaultBody(): array
    {
        if ($this->position) {
            if ($this->position->type !== 'KbPositionCustom') {
                throw new Exception('Position must be of type KbPositionCustom');
            }

            return $this->filterPosition($this->position)->toArray();
        }

        return [];
    }

    protected function filterPosition(InvoicePositionDTO $position): Collection
    {
        return collect($position->toArray())->only([
            'amount',
            'unit_id',
            'account_id',
            'tax_id',
            'text',
            'unit_price',
            'discount_in_percent',
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
