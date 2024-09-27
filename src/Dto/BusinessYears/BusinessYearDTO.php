<?php

namespace CodebarAg\Bexio\Dto\BusinessYears;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class BusinessYearDTO extends Data
{
    public function __construct(
        public int $id,
        public string $start,
        public string $end,
        public string $status,
        public ?string $closed_at = null,
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
            status: Arr::get($data, 'status'),
            closed_at: Arr::get($data, 'closed_at'),
        );
    }
}
