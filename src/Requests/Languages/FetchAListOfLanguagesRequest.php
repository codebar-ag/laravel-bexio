<?php

namespace CodebarAg\Bexio\Requests\Languages;

use CodebarAg\Bexio\Dto\Languages\LanguageDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfLanguagesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly string $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/language';
    }

    public function defaultQuery(): array
    {
        return [
            'order_by' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $languages = collect();

        foreach ($res as $language) {
            $languages->push(LanguageDTO::fromArray($language));
        }

        return $languages;
    }
}
