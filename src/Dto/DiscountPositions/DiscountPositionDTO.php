<?php

namespace CodebarAg\Bexio\Dto\DiscountPositions;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

/**
 * DTO for representing a discount position as returned by the API.
 */
class DiscountPositionDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $text,
        public ?bool $is_percentual,
        public ?string $value,
        public ?string $discount_total,
        public ?string $type = 'KbPositionDiscount',
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
            is_percentual: Arr::get($data, 'is_percentual'),
            value: Arr::get($data, 'value'),
            discount_total: Arr::get($data, 'discount_total'),
            type: Arr::get($data, 'type', 'KbPositionDiscount'),
        );
    }
}
