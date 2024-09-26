<?php

namespace CodebarAg\Bexio\Dto\Notes;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditNoteDTO extends Data
{
    public function __construct(
        public int $user_id,
        public string $event_start,
        public string $subject,
        public ?int $contact_id = null,
        public ?string $info = null,
        public ?int $pr_project_id = null,
        public ?int $entry_id = null,
        public ?int $module_id = null,
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
            user_id: Arr::get($data, 'user_id'),
            event_start: Arr::get($data, 'event_start'),
            subject: Arr::get($data, 'subject'),
            contact_id: Arr::get($data, 'contact_id'),
            info: Arr::get($data, 'info'),
            pr_project_id: Arr::get($data, 'pr_project_id'),
            entry_id: Arr::get($data, 'entry_id'),
            module_id: Arr::get($data, 'module_id'),
        );
    }
}
