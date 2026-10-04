<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\Abstractions\InvoicePositionDTO as NewInvoicePositionDTO;
use Exception;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAnInvoiceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly ?InvoiceDTO $invoice = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice';
    }

    public function defaultBody(): array
    {
        if ($this->invoice) {
            // Keep nested position DTOs intact until they are deliberately
            // serialized by filterPositions(). Data::toArray() recursively
            // converts them to arrays before this request can whitelist them.
            $invoice = collect($this->invoice->all());

            return $this->filterInvoice($invoice);
        }

        return [];
    }

    protected function filterInvoice(Collection $invoice): array
    {
        $filteredInvoice = $invoice->only(keys: [
            'title',
            'contact_id',
            'contact_sub_id',
            'user_id',
            'pr_project_id',
            'logopaper_id',
            'language_id',
            'bank_account_id',
            'currency_id',
            'payment_type_id',
            'header',
            'footer',
            'mwst_type',
            'mwst_is_net',
            'show_position_taxes',
            'is_valid_from',
            'is_valid_to',
            'reference',
            'api_reference',
            'viewed_by_client_at',
            'template_slug',
            'positions',
        ]);

        $positions = $invoice->get('positions');

        if ($positions !== null && ! $positions instanceof Collection) {
            throw new InvalidArgumentException('Invoice positions must be an Illuminate collection.');
        }

        $filteredInvoice->put('positions', $this->filterPositions($positions ?? collect()));

        return $filteredInvoice->toArray();
    }

    protected function filterPositions(Collection $positions): Collection
    {
        $allowedKeys = [
            'KbPositionCustom' => [
                'amount',
                'unit_id',
                'account_id',
                'tax_id',
                'text',
                'unit_price',
                'discount_in_percent',
            ],
            'KbPositionArticle' => [
                'amount',
                'unit_id',
                'account_id',
                'tax_id',
                'text',
                'unit_price',
                'discount_in_percent',
                'article_id',
            ],
            'KbPositionText' => [
                'text',
                'show_pos_nr',
            ],
            'KbPositionSubtotal' => [
                'text',
            ],
            'KbPositionPagebreak' => [
                'pagebreak',
            ],
            'KbPositionDiscount' => [
                'text',
                'is_percentual',
                'value',
            ],
        ];

        return $positions->map(function (InvoicePositionDTO|NewInvoicePositionDTO $position) use ($allowedKeys) {
            $type = $position->type;

            if (! isset($allowedKeys[$type])) {
                throw new InvalidArgumentException("Unsupported invoice position type: {$type}");
            }

            return collect($position->toArray())->only(
                array_merge(['type'], $allowedKeys[$type])
            )->filter(fn ($value) => $value !== null);
        })->values();
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
