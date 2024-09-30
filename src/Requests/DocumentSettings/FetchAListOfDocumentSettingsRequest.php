<?php

namespace CodebarAg\Bexio\Requests\DocumentSettings;

use CodebarAg\Bexio\Dto\DocumentSettings\DocumentSettingDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfDocumentSettingsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly string $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_item_setting';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->orderBy,
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

        $documentSettings = collect();

        foreach ($res as $documentSetting) {
            $documentSettings->push(DocumentSettingDTO::fromArray($documentSetting));
        }

        return $documentSettings;
    }
}
