<?php

namespace CodebarAg\Bexio\Dto\Payments;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class PaymentDTO extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        public ?array $sender,
        public ?array $recipient,
        public float $amount,
        public string $currency,
        public ?string $execution_date,
        public ?string $allowance,
        public bool $is_salary,
        public ?string $instruction_id,
        public ?array $purchase_reference,
        public ?string $document_no,
        public ?string $qr_reference_number,
        public ?string $additional_information,
        public string $status,
        public string $type,
        public ?string $due_date,
        public ?string $created_at,
        public ?bool $is_editing_restricted,
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
            uuid: Arr::get($data, 'uuid'),
            sender: Arr::get($data, 'sender'),
            recipient: Arr::get($data, 'recipient'),
            amount: (float) Arr::get($data, 'amount'),
            currency: Arr::get($data, 'currency'),
            execution_date: Arr::get($data, 'execution_date'),
            allowance: Arr::get($data, 'allowance'),
            is_salary: (bool) Arr::get($data, 'is_salary', false),
            instruction_id: Arr::get($data, 'instruction_id'),
            purchase_reference: Arr::get($data, 'purchase_reference'),
            document_no: Arr::get($data, 'document_no'),
            qr_reference_number: Arr::get($data, 'qr_reference_number'),
            additional_information: Arr::get($data, 'additional_information'),
            status: Arr::get($data, 'status'),
            type: Arr::get($data, 'type'),
            due_date: Arr::get($data, 'due_date'),
            created_at: Arr::get($data, 'created_at'),
            is_editing_restricted: Arr::get($data, 'is_editing_restricted'),
        );
    }
}
