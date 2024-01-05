<?php

namespace CodebarAg\Bexio\Requests\Salutations;

use CodebarAg\Bexio\Dto\Salutations\SalutationDTO;
use CodebarAg\Bexio\Enums\SearchCriteriaEnum;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchSalutationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly string $searchField,
        readonly string $searchTerm,
        readonly string|SearchCriteriaEnum $searchCriteria = '=',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/salutation/search';
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
            [
                'field' => $this->searchField,
                'value' => $this->searchTerm,
                'criteria' => $this->searchCriteria instanceof SearchCriteriaEnum ? $this->searchCriteria->value : $this->searchCriteria,
            ],
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $salutations = collect();

        foreach ($res as $salutation) {
            $salutations->push(SalutationDTO::fromArray($salutation));
        }

        return $salutations;
    }
}
