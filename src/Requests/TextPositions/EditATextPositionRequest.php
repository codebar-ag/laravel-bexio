<?php

namespace CodebarAg\Bexio\Requests\TextPositions;

use CodebarAg\Bexio\Dto\TextPositions\CreateEditTextPositionDTO;
use CodebarAg\Bexio\Dto\TextPositions\TextPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditATextPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public int $positionId,
        public CreateEditTextPositionDTO $position
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_text/{$this->positionId}";
    }

    protected function defaultBody(): array
    {
        $body = $this->position;

        if (! $body instanceof CreateEditTextPositionDTO) {
            $body = CreateEditTextPositionDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): TextPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return TextPositionDTO::fromArray($response->json());
    }
}
