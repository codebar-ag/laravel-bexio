<?php

namespace CodebarAg\Bexio\Requests\SubPositions;

use CodebarAg\Bexio\Dto\SubPositions\CreateEditSubPositionDTO;
use CodebarAg\Bexio\Dto\SubPositions\SubPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateASubPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $kb_document_type,
        public readonly int $document_id,
        public readonly CreateEditSubPositionDTO $position,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf('/2.0/%s/%s/kb_position_subposition', $this->kb_document_type, $this->document_id);
    }

    public function defaultBody(): array
    {
        return $this->position->toArray();
    }

    public function createDtoFromResponse(Response $response): SubPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return SubPositionDTO::fromArray($response->json());
    }
}
