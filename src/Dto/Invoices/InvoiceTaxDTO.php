<?php

namespace CodebarAg\Bexio\Dto\Invoices;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class InvoiceTaxDTO extends Data
{
    public function __construct(
        public float $percentage,
        public float $value,
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
            percentage: Arr::get($data, 'percentage'),
            value: Arr::get($data, 'value'),
        );
    }
}
