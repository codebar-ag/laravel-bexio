<?php

namespace CodebarAg\Bexio\Requests\ItemPositions;

use CodebarAg\Bexio\Dto\ItemPositions\CreateItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAnItemPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public CreateItemPositionDTO $position
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_article";
    }

    protected function defaultBody(): array
    {
        $body = $this->position;

        if (! $body instanceof CreateItemPositionDTO) {
            $body = CreateItemPositionDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): ItemPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ItemPositionDTO::fromArray($response->json());
    }
}
