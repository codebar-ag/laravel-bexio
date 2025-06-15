<?php

namespace CodebarAg\Bexio\Requests\SubPositions;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteASubPositionRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public int $positionId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_subposition/{$this->positionId}";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }
        return $response->json();
    }
}
