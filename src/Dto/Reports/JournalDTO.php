<?php

namespace CodebarAg\Bexio\Dto\Reports;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class JournalDTO extends Data
{
    public function __construct(
        public int $id,
        public string $date,
        public int $debit_account_id,
        public int $credit_account_id,
        public string $description,
        public float $amount,
        public int $currency_id,
        public float $currency_factor,
        public int $base_currency_id,
        public float $base_currency_amount,
        public ?int $ref_id = null,
        public ?string $ref_uuid = null,
        public ?string $ref_class = null,
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
            date: Arr::get($data, 'date'),
            debit_account_id: Arr::get($data, 'debit_account_id'),
            credit_account_id: Arr::get($data, 'credit_account_id'),
            description: Arr::get($data, 'description'),
            amount: Arr::get($data, 'amount'),
            currency_id: Arr::get($data, 'currency_id'),
            currency_factor: Arr::get($data, 'currency_factor'),
            base_currency_id: Arr::get($data, 'base_currency_id'),
            base_currency_amount: Arr::get($data, 'base_currency_amount'),
            ref_id: Arr::get($data, 'ref_id'),
            ref_uuid: Arr::get($data, 'ref_uuid'),
            ref_class: Arr::get($data, 'ref_class'),
        );
    }
}
