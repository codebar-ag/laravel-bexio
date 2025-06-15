<?php

namespace CodebarAg\Bexio\Dto\SubPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for creating or editing a subtotal position for a document (invoice, offer, order).
 * Used as the request payload for the kb_position_subtotal endpoint.
 */
class CreateEditSubPositionDTO extends Data
{
    public function __construct(
        public string $text,
        public bool $show_pos_nr,
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }
        return new self(
            text: Arr::get($data, 'text'),
            show_pos_nr: Arr::get($data, 'show_pos_nr'),
        );
    }
}
