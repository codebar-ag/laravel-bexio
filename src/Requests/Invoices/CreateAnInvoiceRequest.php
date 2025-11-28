<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\Abstractions\InvoicePositionDTO as NewInvoicePositionDTO;
use Exception;
use Illuminate\Support\Collection;
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
            $invoice = collect($this->invoice->toArray());

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

        $filteredInvoice->put('positions', $this->filterPositions($invoice->get('positions')));

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
            return collect($position->toArray())->only(
                array_merge(['type'], $allowedKeys[$position->type])
            );
        });
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
