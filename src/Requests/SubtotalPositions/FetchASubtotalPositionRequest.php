<?php

namespace CodebarAg\Bexio\Requests\SubtotalPositions;

use CodebarAg\Bexio\Dto\SubtotalPositions\SubtotalPositionDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchASubtotalPositionRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public int $positionId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_subtotal/{$this->positionId}";
    }

    public function createDtoFromResponse(Response $response): SubtotalPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return SubtotalPositionDTO::fromArray($response->json());
    }
}
