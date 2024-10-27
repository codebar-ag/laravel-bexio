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
        public string $type,
        public array $bank_account,
        public array $payment,
        public string $instruction_id,
        public string $status,
        public string $created_at,
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
            uuid: Arr::get($data, 'uuid'),
            type: Arr::get($data, 'type'),
            bank_account: Arr::get($data, 'bank_account'),
            payment: Arr::get($data, 'payment'),
            instruction_id: Arr::get($data, 'instruction_id'),
            status: Arr::get($data, 'status'),
            created_at: Arr::get($data, 'created_at'),
        );
    }
}
