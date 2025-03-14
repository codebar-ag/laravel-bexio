<?php

namespace CodebarAg\Bexio\Dto\Invoices;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class PdfDTO extends Data
{
    public function __construct(
        public string $name,
        public int $size,
        public string $mime,
        public string $content,
    ) {}

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
            size: Arr::get($data, 'size'),
            mime: Arr::get($data, 'mime'),
            content: Arr::get($data, 'content'),
        );
    }
}
