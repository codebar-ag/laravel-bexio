<?php

namespace CodebarAg\Bexio\Requests\Titles;

use CodebarAg\Bexio\Dto\Titles\TitleDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchTitlesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly string $searchField,
        readonly string $searchTerm,
        readonly string $searchCriteria = '=',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/title/search';
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
                'criteria' => $this->searchCriteria,
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
            $salutations->push(TitleDTO::fromArray($salutation));
        }

        return $salutations;
    }
}
