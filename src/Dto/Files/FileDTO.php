<?php

namespace CodebarAg\Bexio\Dto\Files;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class FileDTO extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $name,
        public int $size_in_bytes,
        public string $extension,
        public string $mime_type,
        public int $user_id,
        public bool $is_archived,
        public int $source_id,
        public string $source_type,
        public bool $is_referenced,
        public string $created_at,
        public ?string $uploader_email = null,
        public ?string $processing_source = null,
        public ?string $processing_status = null,
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
            uuid: Arr::get($data, 'uuid'),
            name: Arr::get($data, 'name'),
            size_in_bytes: Arr::get($data, 'size_in_bytes'),
            extension: Arr::get($data, 'extension'),
            mime_type: Arr::get($data, 'mime_type'),
            user_id: Arr::get($data, 'user_id'),
            is_archived: Arr::get($data, 'is_archived'),
            source_id: Arr::get($data, 'source_id'),
            source_type: Arr::get($data, 'source_type'),
            is_referenced: Arr::get($data, 'is_referenced'),
            created_at: Arr::get($data, 'created_at'),
            uploader_email: Arr::get($data, 'uploader_email'),
        );
    }
}
