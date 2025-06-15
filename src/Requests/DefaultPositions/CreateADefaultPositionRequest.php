<?php

namespace CodebarAg\Bexio\Requests\DefaultPositions;

use CodebarAg\Bexio\Dto\DefaultPositions\CreateEditDefaultPositionDTO;
use CodebarAg\Bexio\Dto\DefaultPositions\DefaultPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateADefaultPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public CreateEditDefaultPositionDTO $position
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_custom";
    }

    public function defaultBody(): array
    {
        $body = $this->position;

        if (! $body instanceof CreateEditDefaultPositionDTO) {
            $body = CreateEditDefaultPositionDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): DefaultPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return DefaultPositionDTO::fromArray($response->json());
    }
}
