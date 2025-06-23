<?php

namespace CodebarAg\Bexio\Dto\PagebreakPositions;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

/**
 * DTO for representing a pagebreak position as returned by the API.
 */
class PagebreakPositionDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $internal_pos,
        public ?bool $is_optional,
        public ?string $type,
        public ?int $parent_id,
    ) {}

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new Exception('Failed to create DTO from Response');
        }
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            id: Arr::get($data, 'id'),
            internal_pos: Arr::get($data, 'internal_pos'),
            is_optional: Arr::get($data, 'is_optional'),
            type: Arr::get($data, 'type', 'KbPositionPagebreak'),
            parent_id: Arr::get($data, 'parent_id'),
        );
    }
}
