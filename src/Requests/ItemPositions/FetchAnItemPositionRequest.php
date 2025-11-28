<?php

namespace CodebarAg\Bexio\Requests\ItemPositions;

use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAnItemPositionRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $item_position_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_position/'.$this->item_position_id;
    }

    public function createDtoFromResponse(Response $response): ItemPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $itemPosition = $response->json();

        return ItemPositionDTO::fromArray($itemPosition);
    }
}
