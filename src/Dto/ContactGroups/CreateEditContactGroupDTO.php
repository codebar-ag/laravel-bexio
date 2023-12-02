<?php

namespace CodebarAg\Bexio\Dto\ContactGroups;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditContactGroupDTO extends Data
{
    public function __construct(
        public string $name,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new \Exception('Failed to get all tickets', $response->status());
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
        );
    }
}
