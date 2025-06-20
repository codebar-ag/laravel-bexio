<?php

namespace CodebarAg\Bexio\Dto\ItemPositions;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

/**
 * DTO for representing an item position as returned by the API.
 */
class ItemPositionDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $amount,
        public ?int $unit_id,
        public ?string $unit_name,
        public ?int $account_id,
        public ?int $tax_id,
        public ?string $tax_value,
        public ?string $text,
        public ?string $unit_price,
        public ?string $discount_in_percent,
        public ?string $position_total,
        public ?int $parent_id,
        public ?int $article_id,
        public ?string $type = 'KbPositionArticle',
        public ?string $pos,
        public ?string $internal_pos,
        public ?bool $is_optional,
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
            amount: Arr::get($data, 'amount'),
            unit_id: Arr::get($data, 'unit_id'),
            unit_name: Arr::get($data, 'unit_name'),
            account_id: Arr::get($data, 'account_id'),
            tax_id: Arr::get($data, 'tax_id'),
            tax_value: Arr::get($data, 'tax_value'),
            text: Arr::get($data, 'text'),
            unit_price: Arr::get($data, 'unit_price'),
            discount_in_percent: Arr::get($data, 'discount_in_percent'),
            position_total: Arr::get($data, 'position_total'),
            parent_id: Arr::get($data, 'parent_id'),
            article_id: Arr::get($data, 'article_id'),
            type: Arr::get($data, 'type', 'KbPositionArticle'),
            pos: Arr::get($data, 'pos'),
            internal_pos: Arr::get($data, 'internal_pos'),
            is_optional: Arr::get($data, 'is_optional'),
        );
    }
}
