<?php

namespace CodebarAg\Bexio\Dto\Countries;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditCountryDTO extends Data
{
    public function __construct(
        public string $name,
        public string $name_short,
        public string $iso3166_alpha2,
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
            name: Arr::get($data, 'name'),
            name_short: Arr::get($data, 'name_short'),
            iso3166_alpha2: Arr::get($data, 'iso3166_alpha2'),
        );
    }
}
