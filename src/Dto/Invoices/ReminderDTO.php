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
        public ?int $kb_invoice_id,
        public ?string $title,
        public ?string $is_valid_from,
        public ?string $is_valid_to,
        public ?int $reminder_period_in_days,
        public ?int $reminder_level,
        public ?bool $show_positions,
        public ?string $remaining_price,
        public ?string $received_total,
        public ?bool $is_sent,
        public ?string $header,
        public ?string $footer,
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
            kb_invoice_id: Arr::get($data, 'kb_invoice_id'),
            title: Arr::get($data, 'title'),
            is_valid_from: Arr::get($data, 'is_valid_from'),
            is_valid_to: Arr::get($data, 'is_valid_to'),
            reminder_period_in_days: Arr::get($data, 'reminder_period_in_days'),
            reminder_level: Arr::get($data, 'reminder_level'),
            show_positions: Arr::get($data, 'show_positions', false),
            remaining_price: Arr::get($data, 'remaining_price'),
            received_total: Arr::get($data, 'received_total'),
            is_sent: Arr::get($data, 'is_sent', false),
            header: Arr::get($data, 'header'),
            footer: Arr::get($data, 'footer'),
        );
    }
}
