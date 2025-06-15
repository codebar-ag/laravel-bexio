<?php

namespace CodebarAg\Bexio\Requests\SubtotalPositions;

use CodebarAg\Bexio\Dto\SubtotalPositions\CreateEditSubtotalPositionDTO;
use CodebarAg\Bexio\Dto\SubtotalPositions\SubtotalPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateASubtotalPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public array|CreateEditSubtotalPositionDTO $position
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_subtotal";
    }

    protected function defaultBody(): array
    {
        $body = $this->position;
        if (! $body instanceof CreateEditSubtotalPositionDTO) {
            $body = CreateEditSubtotalPositionDTO::fromArray($body);
        }
        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): SubtotalPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }
        return SubtotalPositionDTO::fromArray($response->json());
    }
}
