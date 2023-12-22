<?php

namespace CodebarAg\Bexio\Dto\Files;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class FileUsageDTO extends Data
{
    public function __construct(
        public int $id,
        public string $ref_class,
        public string $title,
        public string $document_nr,
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
            ref_class: Arr::get($data, 'ref_class'),
            title: Arr::get($data, 'title'),
            document_nr: Arr::get($data, 'document_nr'),
        );
    }
}
