<?php

namespace CodebarAg\Bexio\Requests\ManualEntries;

use CodebarAg\Bexio\Dto\ManualEntries\FileDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchFilesOfAccountingEntryRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $manual_entry_id,
        readonly int $entry_id,
        readonly int $limit = 2000,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/manual_entries/'.$this->manual_entry_id.'/entries/'.$this->entry_id.'/files';
    }

    public function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return collect($response->json())->map(fn (array $data) => FileDTO::fromArray($data));
    }
}
