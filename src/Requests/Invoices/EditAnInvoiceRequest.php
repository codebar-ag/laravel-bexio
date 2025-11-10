<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use Exception;
use Illuminate\Support\Collection;
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
        public readonly int $invoice_id,
        public readonly ?InvoiceDTO $invoice = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id;
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
            'id',
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
        ]);

        return $filteredInvoice->toArray();
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
