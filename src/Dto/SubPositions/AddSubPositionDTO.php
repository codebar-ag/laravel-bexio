<?php

namespace CodebarAg\Bexio\Dto\SubPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for adding a sub position to a document (invoice, offer, order).
 * Used in the positions array when creating a document (invoice, offer, order) via the Bexio API.
 */
class AddSubPositionDTO extends Data
{
    public function __construct(
        public string $text,
        public bool $show_pos_nr,
        public string $type = 'KbPositionSubposition',
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            text: Arr::get($data, 'text'),
            show_pos_nr: Arr::get($data, 'show_pos_nr'),
            type: Arr::get($data, 'type', 'KbPositionSubposition'),
        );
    }
}
