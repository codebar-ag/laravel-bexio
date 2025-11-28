<?php

namespace CodebarAg\Bexio\Requests\ItemPositions;

use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfItemPositionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $kb_document_id,
        public readonly string $kb_document_type,
        public readonly string $orderBy = 'id',
        public readonly int $limit = 500,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_position';
    }

    public function defaultQuery(): array
    {
        return [
            'kb_document_id' => $this->kb_document_id,
            'kb_document_type' => $this->kb_document_type,
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

        $itemPositions = collect();

        foreach ($res as $itemPosition) {
            $itemPositions->push(ItemPositionDTO::fromArray($itemPosition));
        }

        return $itemPositions;
    }
}
