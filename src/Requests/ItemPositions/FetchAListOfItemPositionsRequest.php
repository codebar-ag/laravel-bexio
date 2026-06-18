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
        public readonly string $kb_document_type,
        public readonly int $document_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf('/2.0/%s/%s/kb_position_article', $this->kb_document_type, $this->document_id);
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return collect($response->json())
            ->map(fn (array $itemPosition) => ItemPositionDTO::fromArray($itemPosition));
    }
}
