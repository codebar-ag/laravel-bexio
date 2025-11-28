<?php

namespace CodebarAg\Bexio\Dto\ItemPositions;

class OfferItemPositionDTO extends CreateEditItemPositionDTO
{
    public function __construct(
        ?int $kb_position_id = null,
        ?string $type = null,
        ?string $amount = null,
        ?int $unit_id = null,
        ?int $account_id = null,
        ?int $tax_id = null,
        ?string $text = null,
        ?string $unit_price = null,
        ?string $discount_in_percent = null,
        ?int $parent_id = null,
        ?int $article_id = null,
        ?bool $show_pos_nr = null,
        ?bool $pagebreak = null,
        ?bool $is_percentual = null,
        ?string $value = null,
        ?bool $is_optional = null,
        ?string $kb_document_type = null,
    ) {
        parent::__construct(
            kb_document_type: $kb_document_type ?? 'kb_offer',
            kb_position_id: $kb_position_id,
            type: $type,
            amount: $amount,
            unit_id: $unit_id,
            account_id: $account_id,
            tax_id: $tax_id,
            text: $text,
            unit_price: $unit_price,
            discount_in_percent: $discount_in_percent,
            parent_id: $parent_id,
            article_id: $article_id,
            show_pos_nr: $show_pos_nr,
            pagebreak: $pagebreak,
            is_percentual: $is_percentual,
            value: $value,
            is_optional: $is_optional,
        );
    }
}
