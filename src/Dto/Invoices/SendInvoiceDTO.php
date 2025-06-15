<?php

namespace CodebarAg\Bexio\Dto\Invoices;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class SendInvoiceDTO extends Data
{
    public function __construct(
        public string $recipient_email,
        public string $subject,
        public string $message,
        public bool $mark_as_open,
        public ?bool $attach_pdf = null,
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            recipient_email: Arr::get($data, 'recipient_email'),
            subject: Arr::get($data, 'subject'),
            message: Arr::get($data, 'message'),
            mark_as_open: Arr::get($data, 'mark_as_open'),
            attach_pdf: Arr::get($data, 'attach_pdf'),
        );
    }
}
