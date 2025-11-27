<?php

namespace CodebarAg\Bexio\Requests\ItemPositions;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteAnItemPositionRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $item_position_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_position/'.$this->item_position_id;
    }

    /**
     * @throws \JsonException
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
