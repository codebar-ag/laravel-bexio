<?php

namespace CodebarAg\Bexio\Dto\ManualEntries;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class EntryDTO extends Data
{
    public function __construct(
        public string $date,
        public int $debit_account_id,
        public int $credit_account_id,
        public int $tax_id,
        public int $tax_account_id,
        public string $description,
        public float $amount,
        public int $currency_id,
        public int $base_currency_id,
        public int $currency_factor,
        public float $base_currency_amount,
        public int $created_by_user_id,
        public int $edited_by_user_id,
        public ?int $id = null,
    ) {
    }

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
            date: Arr::get($data, 'date'),
            debit_account_id: Arr::get($data, 'debit_account_id'),
            credit_account_id: Arr::get($data, 'credit_account_id'),
            tax_id: Arr::get($data, 'tax_id'),
            tax_account_id: Arr::get($data, 'tax_account_id'),
            description: Arr::get($data, 'description'),
            amount: Arr::get($data, 'amount'),
            currency_id: Arr::get($data, 'currency_id'),
            base_currency_id: Arr::get($data, 'base_currency_id'),
            currency_factor: Arr::get($data, 'currency_factor'),
            base_currency_amount: Arr::get($data, 'base_currency_amount'),
            created_by_user_id: Arr::get($data, 'created_by_user_id'),
            edited_by_user_id: Arr::get($data, 'edited_by_user_id'),
            id: Arr::get($data, 'id'),
        );
    }
}
