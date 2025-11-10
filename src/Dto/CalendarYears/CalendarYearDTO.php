<?php

namespace CodebarAg\Bexio\Dto\CalendarYears;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CalendarYearDTO extends Data
{
    public function __construct(
        public int $id,
        public string $start,
        public string $end,
        public bool $is_vat_subject,
        public bool $is_annual_reporting,
        public string $created_at,
        public string $updated_at,
        public ?string $vat_accounting_method = null,
        public ?string $vat_accounting_type = null,
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
            start: Arr::get($data, 'start'),
            end: Arr::get($data, 'end'),
            is_vat_subject: Arr::get($data, 'is_vat_subject', false),
            is_annual_reporting: Arr::get($data, 'is_annual_reporting', false),
            created_at: Arr::get($data, 'created_at'),
            updated_at: Arr::get($data, 'updated_at'),
            vat_accounting_method: Arr::get($data, 'vat_accounting_method'),
            vat_accounting_type: Arr::get($data, 'vat_accounting_type'),
        );
    }
}
