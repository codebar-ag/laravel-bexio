<?php

namespace CodebarAg\Bexio\Requests\Items;

use CodebarAg\Bexio\Dto\Items\ItemDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAnItemRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $article_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/article/'.$this->article_id;
    }

    public function createDtoFromResponse(Response $response): ItemDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ItemDTO::fromResponse($response);
    }
}
