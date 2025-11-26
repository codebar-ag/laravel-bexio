<?php

namespace CodebarAg\Bexio\Dto\Items;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ItemDTO extends Data
{
    public function __construct(
        public int $id,
        public int $user_id,
        public int $article_type_id,
        public ?int $contact_id,
        public ?int $master_id,
        public ?string $deliverer_code,
        public ?string $deliverer_name,
        public ?string $deliverer_description,
        public string $intern_code,
        public string $intern_name,
        public ?string $intern_description,
        public ?string $purchase_price,
        public ?string $sale_price,
        public ?float $purchase_total,
        public ?float $sale_total,
        public ?int $currency_id,
        public ?int $tax_income_id,
        public ?int $tax_id,
        public ?int $tax_expense_id,
        public ?int $unit_id,
        public bool $is_stock,
        public ?int $stock_id,
        public ?int $stock_place_id,
        public int $stock_nr,
        public int $stock_min_nr,
        public int $stock_reserved_nr,
        public int $stock_available_nr,
        public int $stock_picked_nr,
        public int $stock_disposed_nr,
        public int $stock_ordered_nr,
        public ?int $width,
        public ?int $height,
        public ?int $weight,
        public ?int $volume,
        public ?string $html_text, // Deprecated
        public ?string $remarks,
        public ?float $delivery_price,
        public ?int $article_group_id,
        public ?int $account_id,
        public ?int $expense_account_id,
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
            id: Arr::get($data, 'id'),
            user_id: Arr::get($data, 'user_id'),
            article_type_id: Arr::get($data, 'article_type_id'),
            contact_id: Arr::get($data, 'contact_id'),
            master_id: Arr::get($data, 'master_id'),
            deliverer_code: Arr::get($data, 'deliverer_code'),
            deliverer_name: Arr::get($data, 'deliverer_name'),
            deliverer_description: Arr::get($data, 'deliverer_description'),
            intern_code: Arr::get($data, 'intern_code'),
            intern_name: Arr::get($data, 'intern_name'),
            intern_description: Arr::get($data, 'intern_description'),
            purchase_price: Arr::get($data, 'purchase_price'),
            sale_price: Arr::get($data, 'sale_price'),
            purchase_total: Arr::get($data, 'purchase_total'),
            sale_total: Arr::get($data, 'sale_total'),
            currency_id: Arr::get($data, 'currency_id'),
            tax_income_id: Arr::get($data, 'tax_income_id'),
            tax_id: Arr::get($data, 'tax_id'),
            tax_expense_id: Arr::get($data, 'tax_expense_id'),
            unit_id: Arr::get($data, 'unit_id'),
            is_stock: Arr::get($data, 'is_stock', false),
            stock_id: Arr::get($data, 'stock_id'),
            stock_place_id: Arr::get($data, 'stock_place_id'),
            stock_nr: Arr::get($data, 'stock_nr', 0),
            stock_min_nr: Arr::get($data, 'stock_min_nr', 0),
            stock_reserved_nr: Arr::get($data, 'stock_reserved_nr', 0),
            stock_available_nr: Arr::get($data, 'stock_available_nr', 0),
            stock_picked_nr: Arr::get($data, 'stock_picked_nr', 0),
            stock_disposed_nr: Arr::get($data, 'stock_disposed_nr', 0),
            stock_ordered_nr: Arr::get($data, 'stock_ordered_nr', 0),
            width: Arr::get($data, 'width'),
            height: Arr::get($data, 'height'),
            weight: Arr::get($data, 'weight'),
            volume: Arr::get($data, 'volume'),
            html_text: Arr::get($data, 'html_text'),
            remarks: Arr::get($data, 'remarks'),
            delivery_price: Arr::get($data, 'delivery_price'),
            article_group_id: Arr::get($data, 'article_group_id'),
            account_id: Arr::get($data, 'account_id'),
            expense_account_id: Arr::get($data, 'expense_account_id'),
        );
    }
}
