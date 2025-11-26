<?php

namespace CodebarAg\Bexio\Requests\Items;

use CodebarAg\Bexio\Dto\Items\ItemDTO;
use CodebarAg\Bexio\Enums\Items\OrderByEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfItemsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly string|OrderByEnum $orderBy = 'id',
        public readonly int $limit = 500,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/article';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->orderBy instanceof OrderByEnum ? $this->orderBy->value : $this->orderBy,
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

        $items = collect();

        foreach ($res as $item) {
            $items->push(ItemDTO::fromArray($item));
        }

        return $items;
    }
}
