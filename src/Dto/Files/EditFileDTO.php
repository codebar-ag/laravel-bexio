<?php

namespace CodebarAg\Bexio\Dto\Files;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class EditFileDTO extends Data
{
    public function __construct(
        public string $name,
        public bool $is_archived,
        public string $source_type,
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
            name: Arr::get($data, 'name'),
            is_archived: Arr::get($data, 'is_archived'),
            source_type: Arr::get($data, 'source_type'),
        );
    }
}
