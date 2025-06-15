<?php

namespace CodebarAg\Bexio\Dto\DiscountPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for creating or editing a discount position for a document (invoice, offer, order).
 * Used as the request payload for the kb_position_discount endpoint.
 */
class CreateEditDiscountPositionDTO extends Data
{
    public function __construct(
        public string $text,
        public bool $is_percentual,
        public string $value,
        public ?int $parent_id = null,
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
            parent_id: Arr::get($data, 'parent_id'),
        );
    }
}
