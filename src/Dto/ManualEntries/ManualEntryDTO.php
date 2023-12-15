<?php

namespace CodebarAg\Bexio\Dto\ManualEntries;

use CodebarAg\Bexio\Enums\ManualEntryTypeEnum;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ManualEntryDTO extends Data
{
    public function __construct(
        public int $id,
        public ManualEntryTypeEnum $type,
        public string $date,
        public string $reference_nr,
        public int $created_by_user_id,
        public int $edited_by_user_id,
        public Collection $entries,
        public bool $is_locked,
        public ?string $locked_info = null,
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
            type: ManualEntryTypeEnum::from(Arr::get($data, 'type')),
            date: Arr::get($data, 'date'),
            reference_nr: Arr::get($data, 'reference_nr'),
            created_by_user_id: Arr::get($data, 'created_by_user_id'),
            edited_by_user_id: Arr::get($data, 'edited_by_user_id'),
            entries: collect(Arr::get($data, 'entries'))->map(fn (array $entry) => EntryDTO::fromArray($entry)),
            is_locked: Arr::get($data, 'is_locked'),
            locked_info: Arr::get($data, 'locked_info'),
        );
    }
}
