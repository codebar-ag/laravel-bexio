<?php

namespace CodebarAg\Bexio\Dto\ItemPositions\Abstractions;

use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use Exception;
use Illuminate\Support\Arr;

class OfferPositionDTO extends ItemPositionDTO
{
    public function __construct(
        ?string $type = 'kb_offer',
        ?int $id = null,
        ?string $amount = null,
        ?int $unit_id = null,
        ?string $unit_name = null,
        ?int $account_id = null,
        ?int $tax_id = null,
        ?string $tax_value = null,
        ?string $text = null,
        ?string $unit_price = null,
        ?string $discount_in_percent = null,
        ?string $position_total = null,
        ?int $parent_id = null,
        ?int $article_id = null,
        ?bool $show_pos_nr = null,
        ?bool $pagebreak = null,
        ?bool $is_percentual = null,
        ?string $value = null,
        ?string $pos = null,
        ?string $internal_pos = null,
        ?bool $is_optional = null,
    ) {
        parent::__construct(
            type: $type,
            id: $id,
            amount: $amount,
            unit_id: $unit_id,
            unit_name: $unit_name,
            account_id: $account_id,
            tax_id: $tax_id,
            tax_value: $tax_value,
            text: $text,
            unit_price: $unit_price,
            discount_in_percent: $discount_in_percent,
            position_total: $position_total,
            parent_id: $parent_id,
            article_id: $article_id,
            show_pos_nr: $show_pos_nr,
            pagebreak: $pagebreak,
            is_percentual: $is_percentual,
            value: $value,
            pos: $pos,
            internal_pos: $internal_pos,
            is_optional: $is_optional,
        );
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            type: Arr::get($data, 'type', 'kb_offer'),
            id: Arr::get($data, 'id'),
            amount: Arr::get($data, 'amount'),
            unit_id: Arr::get($data, 'unit_id'),
            unit_name: Arr::get($data, 'unit_name'),
            account_id: Arr::get($data, 'account_id'),
            tax_id: Arr::get($data, 'tax_id'),
            tax_value: Arr::get($data, 'tax_value'),
            text: Arr::get($data, 'text'),
            unit_price: Arr::get($data, 'unit_price'),
            discount_in_percent: Arr::get($data, 'discount_in_percent'),
            position_total: Arr::get($data, 'position_total'),
            parent_id: Arr::get($data, 'parent_id'),
            article_id: Arr::get($data, 'article_id'),
            show_pos_nr: Arr::get($data, 'show_pos_nr'),
            pagebreak: Arr::get($data, 'pagebreak'),
            is_percentual: Arr::get($data, 'is_percentual'),
            value: Arr::get($data, 'value'),
            pos: Arr::get($data, 'pos'),
            internal_pos: Arr::get($data, 'internal_pos'),
            is_optional: Arr::get($data, 'is_optional'),
        );
    }
}
