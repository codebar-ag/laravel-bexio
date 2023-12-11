<?php

namespace CodebarAg\Bexio\Dto\ManualEntries;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ManualEntryDTO extends Data
{
    public function __construct(
        public int $id,
        public string $type,
        public string $date,
        public string $name,
        public string $reference_number,
        public int $created_by_user_id,
        public int $edited_by_user_id,
        public array $entries,
        public bool $is_locked,
        public string $locked_info,
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
            type: Arr::get($data, 'type'),
            date: Arr::get($data, 'date'),
            name: Arr::get($data, 'name'),
            reference_number: Arr::get($data, 'reference_number'),
            created_by_user_id: Arr::get($data, 'created_by_user_id'),
            edited_by_user_id: Arr::get($data, 'edited_by_user_id'),
            entries: Arr::get($data, 'entries'),
            is_locked: Arr::get($data, 'is_locked'),
            locked_info: Arr::get($data, 'locked_info'),
        );
    }
}
