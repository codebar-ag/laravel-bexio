<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Enums\SearchCriteriaEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchInvoicesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $searchField,
        public readonly string $searchTerm,
        public readonly string|SearchCriteriaEnum $searchCriteria = '=',
        public readonly string $orderBy = 'id',
        public readonly int $limit = 500,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/search';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    protected function defaultBody(): array
    {
        return [
            [
                'field' => $this->searchField,
                'value' => $this->searchTerm,
                'criteria' => $this->searchCriteria instanceof SearchCriteriaEnum ? $this->searchCriteria->value : $this->searchCriteria,
            ],
        ];
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $invoices = collect();

        foreach ($res as $invoice) {
            $invoices->push(InvoiceDTO::fromArray($invoice));
        }

        return $invoices;
    }
}
