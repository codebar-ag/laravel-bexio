<?php

namespace CodebarAg\Bexio\Dto\ItemPositions;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditItemPositionDTO extends Data
{
    public function __construct(
        public ?string $kb_document_type = null,
        public ?int $kb_position_id = null,
        public ?string $type = null,
        public ?string $amount = null,
        public ?int $unit_id = null,
        public ?int $account_id = null,
        public ?int $tax_id = null,
        public ?string $text = null,
        public ?string $unit_price = null,
        public ?string $discount_in_percent = null,
        public ?int $parent_id = null,
        public ?int $article_id = null,
        public ?bool $show_pos_nr = null,
        public ?bool $pagebreak = null,
        public ?bool $is_percentual = null,
        public ?string $value = null,
        public ?bool $is_optional = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new \Exception('Failed to create DTO from Response');
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
            kb_document_type: Arr::get($data, 'kb_document_type'),
            kb_position_id: Arr::get($data, 'kb_position_id'),
            type: Arr::get($data, 'type'),
            amount: Arr::get($data, 'amount'),
            unit_id: Arr::get($data, 'unit_id'),
            account_id: Arr::get($data, 'account_id'),
            tax_id: Arr::get($data, 'tax_id'),
            text: Arr::get($data, 'text'),
            unit_price: Arr::get($data, 'unit_price'),
            discount_in_percent: Arr::get($data, 'discount_in_percent'),
            parent_id: Arr::get($data, 'parent_id'),
            article_id: Arr::get($data, 'article_id'),
            show_pos_nr: Arr::get($data, 'show_pos_nr'),
            pagebreak: Arr::get($data, 'pagebreak'),
            is_percentual: Arr::get($data, 'is_percentual'),
            value: Arr::get($data, 'value'),
            is_optional: Arr::get($data, 'is_optional'),
        );
    }
}
