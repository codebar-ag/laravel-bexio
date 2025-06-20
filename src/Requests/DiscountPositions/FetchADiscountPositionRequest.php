<?php

namespace CodebarAg\Bexio\Requests\DiscountPositions;

use CodebarAg\Bexio\Dto\DiscountPositions\DiscountPositionDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchADiscountPositionRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        public int $positionId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_discount/{$this->positionId}";
    }

    public function createDtoFromResponse(Response $response): DiscountPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return DiscountPositionDTO::fromArray($response->json());
    }
}
