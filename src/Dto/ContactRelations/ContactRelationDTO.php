<?php

namespace CodebarAg\Bexio\Dto\ContactRelations;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ContactRelationDTO extends Data
{
    public function __construct(
        public int $id,
        public int $contact_id,
        public int $contact_sub_id,
        public ?string $description,
        public ?string $updated_at,
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
            id: Arr::get($data, 'id'),
            contact_id: Arr::get($data, 'contact_id'),
            contact_sub_id: Arr::get($data, 'contact_sub_id'),
            description: Arr::get($data, 'description'),
            updated_at: Arr::get($data, 'updated_at'),
        );
    }
}
