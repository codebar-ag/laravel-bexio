<?php

namespace CodebarAg\Bexio\Dto\Salutations;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class SalutationDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
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
            id: Arr::get($data, 'id'),
            name: Arr::get($data, 'name'),
        );
    }
}
