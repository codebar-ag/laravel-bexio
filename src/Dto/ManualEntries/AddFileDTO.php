<?php

namespace CodebarAg\Bexio\Dto\ManualEntries;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class AddFileDTO extends Data
{
    public function __construct(
        public string $name,
        public mixed $absolute_file_path_or_stream,
        public string $filename,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            name: Arr::get($data, 'name'),
            absolute_file_path_or_stream: Arr::get($data, 'absolute_file_path_or_stream'),
            filename: Arr::get($data, 'filename'),
        );
    }
}
