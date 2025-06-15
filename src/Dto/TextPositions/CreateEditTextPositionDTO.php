<?php

namespace CodebarAg\Bexio\Dto\TextPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for creating or editing a text position for a document (invoice, offer, order).
 * Used as the request payload for the kb_position_text endpoint.
 */
class CreateEditTextPositionDTO extends Data
{
    public function __construct(
        public string $text,
        public bool $show_pos_nr,
        public ?int $parent_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }
        return new self(
            text: Arr::get($data, 'text'),
            show_pos_nr: Arr::get($data, 'show_pos_nr'),
            parent_id: Arr::get($data, 'parent_id'),
        );
    }
}
