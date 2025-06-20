<?php

namespace CodebarAg\Bexio\Dto\SubtotalPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for adding a subtotal position to a document (invoice, offer, order).
 * Used in the positions array when creating a document (invoice, offer, order) via the Bexio API.
 */
class AddSubtotalPositionDTO extends Data
{
    public function __construct(
        public string $text,
        public string $type = 'KbPositionSubtotal',
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            text: Arr::get($data, 'text'),
            type: Arr::get($data, 'type', 'KbPositionSubtotal'),
        );
    }
}
