<?php

namespace CodebarAg\Bexio\Dto\DefaultPositions;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class DefaultPositionDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $amount,
        public ?string $amount_reserved,
        public ?string $amount_open,
        public ?string $amount_completed,
        public ?int $unit_id,
        public ?int $account_id,
        public ?string $unit_name,
        public ?int $tax_id,
        public ?string $tax_value,
        public ?string $text,
        public ?string $unit_price,
        public ?string $discount_in_percent,
        public ?string $position_total,
        public int|string|null $pos,
        public int|string|null $internal_pos,
        public ?bool $is_optional,
        public ?string $type,
        public ?int $parent_id,
    ) {}

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new Exception('Failed to create DTO from Response');
        }

        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            id: Arr::get($data, 'id'),
            amount: Arr::get($data, 'amount'),
            amount_reserved: Arr::get($data, 'amount_reserved'),
            amount_open: Arr::get($data, 'amount_open'),
            amount_completed: Arr::get($data, 'amount_completed'),
            unit_id: Arr::get($data, 'unit_id'),
            account_id: Arr::get($data, 'account_id'),
            unit_name: Arr::get($data, 'unit_name'),
            tax_id: Arr::get($data, 'tax_id'),
            tax_value: Arr::get($data, 'tax_value'),
            text: Arr::get($data, 'text'),
            unit_price: Arr::get($data, 'unit_price'),
            discount_in_percent: Arr::get($data, 'discount_in_percent'),
            position_total: Arr::get($data, 'position_total'),
            pos: Arr::get($data, 'pos'),
            internal_pos: Arr::get($data, 'internal_pos'),
            is_optional: Arr::get($data, 'is_optional'),
            type: Arr::get($data, 'type'),
            parent_id: Arr::get($data, 'parent_id'),
        );
    }
}
