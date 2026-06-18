<?php

namespace CodebarAg\Bexio\Dto\Invoices;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ReminderDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $title,
        public ?int $kb_invoice_id,
        public ?int $reminder_level_id,
        public ?bool $is_sent,
        public ?string $is_valid_from,
        public ?string $is_valid_to,
        public ?string $subject,
        public ?string $body,
        public ?int $salutation_id,
        public ?string $updated_at,
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
            title: Arr::get($data, 'title'),
            kb_invoice_id: Arr::get($data, 'kb_invoice_id'),
            reminder_level_id: Arr::get($data, 'reminder_level_id'),
            is_sent: Arr::get($data, 'is_sent'),
            is_valid_from: Arr::get($data, 'is_valid_from'),
            is_valid_to: Arr::get($data, 'is_valid_to'),
            subject: Arr::get($data, 'subject'),
            body: Arr::get($data, 'body'),
            salutation_id: Arr::get($data, 'salutation_id'),
            updated_at: Arr::get($data, 'updated_at'),
        );
    }
}
