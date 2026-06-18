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

class EditAnItemPositionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $kb_document_type,
        public readonly int $document_id,
        public readonly int $item_position_id,
        public readonly ?CreateEditItemPositionDTO $itemPosition = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf(
            '/2.0/%s/%s/kb_position_article/%s',
            $this->kb_document_type,
            $this->document_id,
            $this->item_position_id,
        );
    }

    public function defaultBody(): array
    {
        if (! $this->itemPosition) {
            return [];
        }

        // Note: the edit endpoint rejects `article_id` ("Widget schema does not include
        // the following field(s): article_id"), unlike create — so it is omitted here.
        return collect($this->itemPosition->toArray())->only([
            'amount',
            'unit_id',
            'account_id',
            'tax_id',
            'text',
            'unit_price',
            'discount_in_percent',
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
