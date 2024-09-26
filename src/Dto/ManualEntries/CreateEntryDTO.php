<?php

namespace CodebarAg\Bexio\Dto\ManualEntries;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class CreateEntryDTO extends Data
{
    public function __construct(
        public int $tax_id,
        public int $tax_account_id,
        public string $description,
        public float $amount,
        public int $currency_id,
        public ?int $currency_factor = null,
        public ?int $debit_account_id = null,
        public ?int $credit_account_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            tax_id: Arr::get($data, 'tax_id'),
            tax_account_id: Arr::get($data, 'tax_account_id'),
            description: Arr::get($data, 'description'),
            amount: Arr::get($data, 'amount'),
            currency_id: Arr::get($data, 'currency_id'),
            currency_factor: Arr::get($data, 'currency_factor'),
            debit_account_id: Arr::get($data, 'debit_account_id'),
            credit_account_id: Arr::get($data, 'credit_account_id'),
        );
    }
}
