<?php

namespace CodebarAg\Bexio\Requests\ContactRelations;

use CodebarAg\Bexio\Dto\ContactRelations\ContactRelationDTO;
use CodebarAg\Bexio\Enums\SearchCriteriaEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchContactRelationsRequest extends Request implements HasBody
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
        return '/2.0/contact_relation/search';
    }

    public function defaultQuery(): array
    {
        return [
            'orderBy' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'query' => [
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

        $contacts = collect();

        foreach ($res as $contact) {
            $contacts->push(ContactRelationDTO::fromArray($contact));
        }

        return $contacts;
    }
}
