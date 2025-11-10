<?php

namespace CodebarAg\Bexio\Requests\ManualEntries;

use CodebarAg\Bexio\Dto\ManualEntries\ManualEntryDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfManualEntriesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $limit = 2000,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/manual_entries';
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

        $manualEntries = collect();

        foreach ($res as $manualEntry) {
            $manualEntries->push(ManualEntryDTO::fromArray($manualEntry));
        }

        return $manualEntries;
    }
}
