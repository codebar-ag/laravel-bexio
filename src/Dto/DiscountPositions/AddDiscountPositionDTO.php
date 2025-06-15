<?php

namespace CodebarAg\Bexio\Dto\DiscountPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for adding a discount position to a document (invoice, offer, order).
 * Used in the positions array when creating a document (invoice, offer, order) via the Bexio API.
 */
class AddDiscountPositionDTO extends Data
{
    public function __construct(
        public string $text,
        public bool $is_percentual,
        public string $value,
        public string $type = 'KbPositionDiscount',
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }
        return new self(
            text: Arr::get($data, 'text'),
            is_percentual: Arr::get($data, 'is_percentual'),
            value: Arr::get($data, 'value'),
            type: Arr::get($data, 'type', 'KbPositionDiscount'),
        );
    }
}
