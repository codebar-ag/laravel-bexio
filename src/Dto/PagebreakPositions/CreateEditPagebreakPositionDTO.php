<?php

namespace CodebarAg\Bexio\Dto\PagebreakPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for creating or editing a pagebreak position for a document (invoice, offer, order).
 * Used as the request payload for the kb_position_pagebreak endpoint.
 */
class CreateEditPagebreakPositionDTO extends Data
{
    public function __construct(
        public bool $pagebreak,
        public ?int $parent_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }
        return new self(
            pagebreak: Arr::get($data, 'pagebreak'),
            parent_id: Arr::get($data, 'parent_id'),
        );
    }
}
