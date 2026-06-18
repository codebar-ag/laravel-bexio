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
        public ?string $title,
        public ?int $payment_service_id,
        public ?bool $is_client_account_redemption,
        public ?bool $is_cash_discount,
        public ?int $kb_invoice_id,
        public ?int $kb_credit_voucher_id,
        public ?int $kb_bill_id,
        public ?string $kb_credit_voucher_text,
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
            title: Arr::get($data, 'title'),
            payment_service_id: Arr::get($data, 'payment_service_id'),
            is_client_account_redemption: Arr::get($data, 'is_client_account_redemption', false),
            is_cash_discount: Arr::get($data, 'is_cash_discount', false),
            kb_invoice_id: Arr::get($data, 'kb_invoice_id'),
            kb_credit_voucher_id: Arr::get($data, 'kb_credit_voucher_id'),
            kb_bill_id: Arr::get($data, 'kb_bill_id'),
            kb_credit_voucher_text: Arr::get($data, 'kb_credit_voucher_text'),
        );
    }
}
