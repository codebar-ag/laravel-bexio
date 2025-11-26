<?php

namespace CodebarAg\Bexio\Dto\Items;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditItemDTO extends Data
{
    public function __construct(
        public ?int $user_id,
        public int $article_type_id,
        public ?int $contact_id,
        public ?string $deliverer_code,
        public ?string $deliverer_name,
        public ?string $deliverer_description,
        public string $intern_code,
        public string $intern_name,
        public ?string $intern_description = null,
        public ?string $purchase_price = null,
        public ?string $sale_price = null,
        public ?float $purchase_total = null,
        public ?float $sale_total = null,
        public ?int $currency_id = null,
        public ?int $tax_income_id = null,
        public ?int $tax_id = null,
        public ?int $tax_expense_id = null,
        public ?int $unit_id = null,
        public bool $is_stock = false,
        public ?int $stock_id = null,
        public ?int $stock_place_id = null,
        public int $stock_nr = 0,
        public int $stock_min_nr = 0,
        public ?int $width = null,
        public ?int $height = null,
        public ?int $weight = null,
        public ?int $volume = null,
        public ?string $html_text = null, // Deprecated
        public ?string $remarks = null,
        public ?float $delivery_price = null,
        public ?int $article_group_id = null,
        public ?int $account_id = null,
        public ?int $expense_account_id = null,
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
            user_id: Arr::get($data, 'user_id'),
            article_type_id: Arr::get($data, 'article_type_id'),
            contact_id: Arr::get($data, 'contact_id'),
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
