<?php

namespace CodebarAg\Bexio\Requests\DiscountPositions;

use CodebarAg\Bexio\Dto\DiscountPositions\CreateEditDiscountPositionDTO;
use CodebarAg\Bexio\Dto\DiscountPositions\DiscountPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditADiscountPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public int $positionId,
        public array|CreateEditDiscountPositionDTO $position
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_discount/{$this->positionId}";
    }

    protected function defaultBody(): array
    {
        $body = $this->position;
        if (! $body instanceof CreateEditDiscountPositionDTO) {
            $body = CreateEditDiscountPositionDTO::fromArray($body);
        }
        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): DiscountPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }
        return DiscountPositionDTO::fromArray($response->json());
    }
}
