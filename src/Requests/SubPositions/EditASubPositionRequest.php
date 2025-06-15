<?php

namespace CodebarAg\Bexio\Requests\SubPositions;

use CodebarAg\Bexio\Dto\SubPositions\CreateEditSubPositionDTO;
use CodebarAg\Bexio\Dto\SubPositions\SubPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class EditASubPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public int $positionId,
        public array|CreateEditSubPositionDTO $position
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_subposition/{$this->positionId}";
    }

    protected function defaultBody(): array
    {
        $body = $this->position;
        if (! $body instanceof CreateEditSubPositionDTO) {
            $body = CreateEditSubPositionDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse($response): SubPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return SubPositionDTO::fromArray($response->json());
    }
}
