<?php

namespace CodebarAg\Bexio\Requests\DiscountPositions;

use CodebarAg\Bexio\Dto\DiscountPositions\DiscountPositionDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfDiscountPositionsRequest extends Request
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
        return "/2.0/{$this->kbDocumentType}/{$this->documentId}/kb_position_discount";
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
            $positions->push(DiscountPositionDTO::fromArray($position));
        }
        return $positions;
    }
}
