<?php

namespace CodebarAg\Bexio\Requests\ManualEntries;

use CodebarAg\Bexio\Dto\ManualEntries\CreateManualEntryDTO;
use CodebarAg\Bexio\Dto\ManualEntries\ManualEntryDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateManualEntryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array|CreateManualEntryDTO $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/manual_entries';
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateManualEntryDTO) {
            $body = CreateManualEntryDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ManualEntryDTO::fromArray($response->json());
    }
}
