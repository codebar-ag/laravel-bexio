<?php

namespace CodebarAg\Bexio\Dto\SubtotalPositions;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

/**
 * DTO for representing a subtotal position as returned by the API.
 */
class SubtotalPositionDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $text,
        public ?string $value,
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
            text: Arr::get($data, 'text'),
            value: Arr::get($data, 'value'),
            internal_pos: Arr::get($data, 'internal_pos'),
            is_optional: Arr::get($data, 'is_optional'),
            type: Arr::get($data, 'type', 'KbPositionSubtotal'),
            parent_id: Arr::get($data, 'parent_id'),
        );
    }
}
