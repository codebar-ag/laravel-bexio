<?php

namespace CodebarAg\Bexio\Dto\PagebreakPositions;

/**
 * DTO for adding a pagebreak position to a document (invoice, offer, order).
 * Used in the positions array when creating a document (invoice, offer, order) via the Bexio API.
 */

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class AddPagebreakPositionDTO extends Data
{
    public function __construct(
        public bool $pagebreak,
        public string $type = 'KbPositionPagebreak',
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }
        return new self(
            pagebreak: Arr::get($data, 'pagebreak'),
            type: Arr::get($data, 'type', 'KbPositionPagebreak'),
        );
    }
}
