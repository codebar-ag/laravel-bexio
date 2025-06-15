<?php

namespace CodebarAg\Bexio\Requests\SubtotalPositions;

use CodebarAg\Bexio\Dto\SubtotalPositions\SubtotalPositionDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfSubtotalPositionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public string $kbDocumentType, // e.g. 'kb_invoice'
        public int $documentId,
        readonly int $limit = 500,
        readonly int $offset = 0
    ) {}

    public function resolveEndpoint(): string
    {
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_subtotal";
    }

    public function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }
        $res = $response->json();
        $positions = collect();
        foreach ($res as $position) {
            $positions->push(SubtotalPositionDTO::fromArray($position));
        }
        return $positions;
    }
}
