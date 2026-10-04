<?php

namespace CodebarAg\Bexio\Dto\DefaultPositions;

use Spatie\LaravelData\Data;

class CreateEditDefaultPositionDTO extends Data
{
    public function __construct(
        public string $amount,
        public int $unit_id,
        public int $account_id,
        public int $tax_id,
        public string $text,
        public string $unit_price,
        public ?string $discount_in_percent = null,
        public ?bool $is_optional = null,
        public ?int $parent_id = null,
    ) {}
}
