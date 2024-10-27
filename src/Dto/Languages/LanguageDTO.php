<?php

namespace CodebarAg\Bexio\Dto\Languages;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class LanguageDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $decimal_point,
        public string $thousands_separator,
        public int $date_format_id,
        public string $date_format,
        public string $iso_639_1,
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
            name: Arr::get($data, 'name'),
            decimal_point: Arr::get($data, 'decimal_point'),
            thousands_separator: Arr::get($data, 'thousands_separator'),
            date_format_id: Arr::get($data, 'date_format_id'),
            date_format: Arr::get($data, 'date_format'),
            iso_639_1: Arr::get($data, 'iso_639_1'),
        );
    }
}
