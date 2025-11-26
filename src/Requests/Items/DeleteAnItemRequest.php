<?php

namespace CodebarAg\Bexio\Requests\Items;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteAnItemRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $article_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/article/'.$this->article_id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
