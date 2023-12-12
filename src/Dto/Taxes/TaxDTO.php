<?php

namespace CodebarAg\Bexio\Dto\Taxes;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class TaxDTO extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $name,
        public string $code,
        public string $type,
        public int $account_id,
        public string $tax_settlement_type,
        public float $value,
        public bool $is_active,
        public string $display_name,
        public ?string $digit = null,
        public ?int $start_year = null,
        public ?int $end_year = null,
        public mixed $net_tax_value = null,
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
            id: Arr::get($data, 'id'),
            uuid: Arr::get($data, 'uuid'),
            name: Arr::get($data, 'name'),
            code: Arr::get($data, 'code'),
            type: Arr::get($data, 'type'),
            account_id: Arr::get($data, 'account_id'),
            tax_settlement_type: Arr::get($data, 'tax_settlement_type'),
            value: Arr::get($data, 'value'),
            is_active: Arr::get($data, 'is_active'),
            display_name: Arr::get($data, 'display_name'),
            digit: Arr::get($data, 'digit'),
            start_year: Arr::get($data, 'start_year'),
            end_year: Arr::get($data, 'end_year'),
            net_tax_value: Arr::get($data, 'net_tax_value'),
        );
    }
}
