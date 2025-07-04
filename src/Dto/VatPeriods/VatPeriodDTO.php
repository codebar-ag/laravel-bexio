<?php

namespace CodebarAg\Bexio\Dto\VatPeriods;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class VatPeriodDTO extends Data
{
    public function __construct(
        public int $id,
        public string $start,
        public string $end,
        public string $type,
        public string $status,
        public string $closed_at,
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
            type: Arr::get($data, 'type'),
            status: Arr::get($data, 'status'),
            closed_at: Arr::get($data, 'closed_at'),
        );
    }
}
