<?php

namespace CodebarAg\Bexio\Dto\Currencies;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class EditCurrencyDTO extends Data
{
    public function __construct(
        public float $round_factor,
    ) {
    }

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
            round_factor: Arr::get($data, 'round_factor'),
        );
    }
}
