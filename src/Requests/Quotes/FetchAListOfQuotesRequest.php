<?php

namespace CodebarAg\Bexio\Requests\Quotes;

use CodebarAg\Bexio\Dto\Quotes\QuoteDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfQuotesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $orderBy = 'id',
        public readonly int $limit = 500,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_offer';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $quotes = collect();

        foreach ($res as $quote) {
            $quotes->push(QuoteDTO::fromArray($quote));
        }

        return $quotes;
    }
}
