<?php

namespace CodebarAg\Bexio\Dto\CalendarYears;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateCalendarYearDTO extends Data
{
    public function __construct(
        public string $year,
        public bool $is_vat_subject,
        public bool $is_annual_reporting,
        public string $vat_accounting_method,
        public string $vat_accounting_type,
        public int $default_tax_income_id,
        public int $default_tax_expense_id,
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
            year: Arr::get($data, 'year'),
            is_vat_subject: Arr::get($data, 'is_vat_subject'),
            is_annual_reporting: Arr::get($data, 'is_annual_reporting'),
            vat_accounting_method: Arr::get($data, 'vat_accounting_method'),
            vat_accounting_type: Arr::get($data, 'vat_accounting_type'),
            default_tax_income_id: Arr::get($data, 'default_tax_income_id'),
            default_tax_expense_id: Arr::get($data, 'default_tax_expense_id'),
        );
    }
}
