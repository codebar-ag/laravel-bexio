<?php

namespace CodebarAg\Bexio\Dto\ItemPositions;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for adding an item position to a document (invoice, offer, order).
 * Used in the positions array when creating a document (invoice, offer, order) via the Bexio API.
 */
class AddItemPositionDTO extends Data
{
    public function __construct(
        public string $amount,
        public int $unit_id,
        public int $account_id,
        public int $tax_id,
        public string $text,
        public string $unit_price,
        public string $discount_in_percent,
        public int $article_id,
        public string $type = 'KbPositionArticle',
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            amount: Arr::get($data, 'amount'),
            unit_id: Arr::get($data, 'unit_id'),
            account_id: Arr::get($data, 'account_id'),
            tax_id: Arr::get($data, 'tax_id'),
            text: Arr::get($data, 'text'),
            unit_price: Arr::get($data, 'unit_price'),
            discount_in_percent: Arr::get($data, 'discount_in_percent'),
            article_id: Arr::get($data, 'article_id'),
            type: Arr::get($data, 'type', 'KbPositionArticle'),
        );
    }
}
