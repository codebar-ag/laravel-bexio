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
        public readonly string $kb_document_type,
        public readonly int $document_id,
        public readonly int $item_position_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf(
            '/2.0/%s/%s/kb_position_article/%s',
            $this->kb_document_type,
            $this->document_id,
            $this->item_position_id,
        );
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
