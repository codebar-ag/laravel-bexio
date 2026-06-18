<?php

namespace CodebarAg\Bexio\Requests\ItemPositions;

use CodebarAg\Bexio\Dto\ItemPositions\CreateEditItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAnItemPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $kb_document_type,
        public readonly int $document_id,
        public readonly ?CreateEditItemPositionDTO $itemPosition = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf('/2.0/%s/%s/kb_position_article', $this->kb_document_type, $this->document_id);
    }

    public function defaultBody(): array
    {
        if (! $this->itemPosition) {
            return [];
        }

        return collect($this->itemPosition->toArray())->only([
            'amount',
            'unit_id',
            'account_id',
            'tax_id',
            'text',
            'unit_price',
            'discount_in_percent',
            'article_id',
        ])->filter(fn ($value) => $value !== null)->toArray();
    }

    public function createDtoFromResponse(Response $response): ItemPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ItemPositionDTO::fromArray($response->json());
    }
}
