<?php

namespace CodebarAg\Bexio\Dto\SubPositions;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

/**
 * DTO for representing a subtotal position as returned by the API.
 */
class SubPositionDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $text,
        public ?string $pos,
        public ?string $internal_pos,
        public ?bool $show_pos_nr,
        public ?bool $is_optional,
        public ?string $total_sum,
        public ?bool $show_pos_prices,
        public ?string $type = 'KbPositionSubposition',
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
            pos: Arr::get($data, 'pos'),
            internal_pos: Arr::get($data, 'internal_pos'),
            show_pos_nr: Arr::get($data, 'show_pos_nr'),
            is_optional: Arr::get($data, 'is_optional'),
            total_sum: Arr::get($data, 'total_sum'),
            show_pos_prices: Arr::get($data, 'show_pos_prices'),
            type: Arr::get($data, 'type', 'KbPositionSubposition'),
            parent_id: Arr::get($data, 'parent_id'),
        );
    }
}
