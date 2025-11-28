<?php

namespace CodebarAg\Bexio\Requests\ItemPositions;

use CodebarAg\Bexio\Dto\ItemPositions\CreateEditItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use Exception;
use Illuminate\Support\Collection;
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
        public readonly int $item_position_id,
        public readonly ?CreateEditItemPositionDTO $itemPosition = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_position/'.$this->item_position_id;
    }

    public function defaultBody(): array
    {
        if ($this->itemPosition) {
            $itemPosition = collect($this->itemPosition->toArray());

            return $this->filterItemPosition($itemPosition);
        }

        return [];
    }

    protected function filterItemPosition(Collection $itemPosition): array
    {
        $allowedKeys = [
            'KbPositionCustom' => [
                'amount',
                'unit_id',
                'account_id',
                'tax_id',
                'text',
                'unit_price',
                'discount_in_percent',
            ],
            'KbPositionArticle' => [
                'amount',
                'unit_id',
                'account_id',
                'tax_id',
                'text',
                'unit_price',
                'discount_in_percent',
                'article_id',
            ],
            'KbPositionText' => [
                'text',
                'show_pos_nr',
            ],
            'KbPositionSubtotal' => [
                'text',
            ],
            'KbPositionPagebreak' => [
                'pagebreak',
            ],
            'KbPositionDiscount' => [
                'text',
                'is_percentual',
                'value',
            ],
        ];

        $type = $itemPosition->get('type');
        $keys = array_merge(['type'], $allowedKeys[$type] ?? []);

        return $itemPosition->only($keys)->toArray();
    }

    public function createDtoFromResponse(Response $response): ItemPositionDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        return ItemPositionDTO::fromArray($res);
    }
}
