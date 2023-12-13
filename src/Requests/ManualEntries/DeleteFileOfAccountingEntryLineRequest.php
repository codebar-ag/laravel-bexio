<?php

namespace CodebarAg\Bexio\Requests\ManualEntries;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteFileOfAccountingEntryLineRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        readonly int $manual_entry_id,
        readonly int $entry_id,
        readonly int $file_id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/manual_entries/'.$this->manual_entry_id.'/entries/'.$this->entry_id.'/files/'.$this->file_id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
