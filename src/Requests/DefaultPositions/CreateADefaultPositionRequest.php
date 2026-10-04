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
        public readonly string $kb_document_type,
        public readonly int $document_id,
        public readonly CreateEditDefaultPositionDTO $position,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf('/2.0/%s/%s/kb_position_custom', $this->kb_document_type, $this->document_id);
    }

    public function defaultBody(): array
    {
        return collect($this->position->toArray())
            ->filter(fn ($value) => $value !== null)
            ->toArray();
    }

    public function createDtoFromResponse(Response $response): DefaultPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return DefaultPositionDTO::fromArray($response->json());
    }
}
