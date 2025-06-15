<?php

namespace CodebarAg\Bexio\Requests\PagebreakPositions;

use CodebarAg\Bexio\Dto\PagebreakPositions\CreateEditPagebreakPositionDTO;
use CodebarAg\Bexio\Dto\PagebreakPositions\PagebreakPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditAPagebreakPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public int $positionId,
        public array|CreateEditPagebreakPositionDTO $position
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_pagebreak/{$this->positionId}";
    }

    protected function defaultBody(): array
    {
        $body = $this->position;
        if (! $body instanceof CreateEditPagebreakPositionDTO) {
            $body = CreateEditPagebreakPositionDTO::fromArray($body);
        }
        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): PagebreakPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }
        return PagebreakPositionDTO::fromArray($response->json());
    }
}
