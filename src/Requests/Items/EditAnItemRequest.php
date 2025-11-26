<?php

namespace CodebarAg\Bexio\Requests\Items;

use CodebarAg\Bexio\Dto\Items\CreateEditItemDTO;
use CodebarAg\Bexio\Dto\Items\ItemDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditAnItemRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        public readonly int $article_id,
        protected readonly array|CreateEditItemDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/article/'.$this->article_id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditItemDTO) {
            $body = CreateEditItemDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): ItemDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ItemDTO::fromArray($response->json());
    }
}
