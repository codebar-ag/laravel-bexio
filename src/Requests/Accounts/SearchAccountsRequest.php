<?php

namespace CodebarAg\Bexio\Requests\Accounts;

use CodebarAg\Bexio\Dto\Accounts\AccountDTO;
use CodebarAg\Bexio\Enums\CalendarYears\VatAccountingMethodEnum;
use CodebarAg\Bexio\Enums\SearchCriteriaEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchAccountsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly string|VatAccountingMethodEnum $searchField,
        readonly string $searchTerm,
        readonly string|SearchCriteriaEnum $searchCriteria = '=',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/accounts/search';
    }

    public function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'query' => [
                'field' => $this->searchField instanceof VatAccountingMethodEnum ? $this->searchField->value : $this->searchField,
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

        $accounts = collect();

        foreach ($res as $account) {
            $accounts->push(AccountDTO::fromArray($account));
        }

        return $accounts;
    }
}
