<?php

namespace CodebarAg\Bexio\Dto\Invoices;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class PaymentDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $date,
        public ?string $value,
        public ?int $bank_account_id,
        public ?int $payment_service_id,
        public ?bool $is_cash_discount,
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
            date: Arr::get($data, 'date'),
            value: Arr::get($data, 'value'),
            bank_account_id: Arr::get($data, 'bank_account_id'),
            payment_service_id: Arr::get($data, 'payment_service_id'),
            is_cash_discount: Arr::get($data, 'is_cash_discount'),
        );
    }
}
