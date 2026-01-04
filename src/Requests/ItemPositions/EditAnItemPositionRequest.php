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
        public readonly int $kb_document_id,
        public readonly int $item_position_id,
        public readonly ?CreateEditItemPositionDTO $itemPosition = null,
    ) {}

    public function resolveEndpoint(): string
    {
        $type = $this->itemPosition?->type;
        $documentType = $this->itemPosition?->kb_document_type;

        if (! $type || ! $documentType) {
            throw new Exception('Missing type or kb_document_type for item position.');
        }

        $suffixMap = [
            'KbPositionCustom' => 'kb_position_custom',
            'KbPositionArticle' => 'kb_position_article',
            'KbPositionText' => 'kb_position_text',
            'KbPositionSubtotal' => 'kb_position_subtotal',
            'KbPositionPagebreak' => 'kb_position_pagebreak',
            'KbPositionDiscount' => 'kb_position_discount',
            'KbPositionSubposition' => 'kb_position_subposition',
        ];

        $suffix = $suffixMap[$type] ?? null;
        if (! $suffix) {
            throw new Exception('Unsupported item position type: '.$type);
        }

        return sprintf('/2.0/%s/%d/%s/%d', $documentType, $this->kb_document_id, $suffix, $this->item_position_id);
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
                'parent_id',
            ],
            'KbPositionArticle' => [
                'amount',
                'unit_id',
                'account_id',
                'tax_id',
                'text',
                'unit_price',
                'discount_in_percent',
                'parent_id',
            ],
            'KbPositionText' => [
                'text',
                'show_pos_nr',
                'parent_id',
            ],
            'KbPositionSubtotal' => [
                'text',
                'parent_id',
            ],
            'KbPositionPagebreak' => [
                'pagebreak',
                'parent_id',
            ],
            'KbPositionDiscount' => [
                'text',
                'is_percentual',
                'value',
                'parent_id',
            ],
            'KbPositionSubposition' => [
                'text',
                'show_pos_nr',
            ],
        ];

        $type = $itemPosition->get('type');
        $keys = $allowedKeys[$type] ?? [];

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
