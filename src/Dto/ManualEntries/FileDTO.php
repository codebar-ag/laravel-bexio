<?php

namespace CodebarAg\Bexio\Dto\ManualEntries;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class FileDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $size_in_bytes,
        public string $extension,
        public string $mime_type,
        public int $user_id,
        public string $created_at,
        public ?bool $is_archived = null,
        public ?int $source_id = null,
        public ?bool $is_referenced = null,
        public ?string $source_type = null,
        public ?string $uuid = null,
        public ?string $uploader_email = null,
        public ?string $processing_source = null,
        public ?string $processing_status = null
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
            size_in_bytes: Arr::get($data, 'size_in_bytes'),
            extension: Arr::get($data, 'extension'),
            mime_type: Arr::get($data, 'mime_type'),
            user_id: Arr::get($data, 'user_id'),
            created_at: Arr::get($data, 'created_at'),
            is_archived: Arr::get($data, 'is_archived'),
            source_id: Arr::get($data, 'source_id'),
            is_referenced: Arr::get($data, 'is_referenced'),
            source_type: Arr::get($data, 'source_type'),
            uuid: Arr::get($data, 'uuid'),
            uploader_email: Arr::get($data, 'uploader_email'),
            processing_source: Arr::get($data, 'processing_source'),
            processing_status: Arr::get($data, 'processing_status'),
        );
    }
}
