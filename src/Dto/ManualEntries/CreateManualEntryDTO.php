<?php

namespace CodebarAg\Bexio\Dto\ManualEntries;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateManualEntryDTO extends Data
{
    public function __construct(
        public string $type,
        public string $date,
        public string $reference_nr,
        public Collection $entries,
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
            type: Arr::get($data, 'type'),
            date: Arr::get($data, 'date'),
            reference_nr: Arr::get($data, 'reference_nr'),
            entries: collect(Arr::get($data, 'entries'))->map(fn (array $entry) => EntryDTO::fromArray($entry)),
        );
    }
}
