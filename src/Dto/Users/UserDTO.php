<?php

namespace CodebarAg\Bexio\Dto\Users;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class UserDTO extends Data
{
    public function __construct(
        public int $id,
        public string $salutation_type,
        public ?string $firstname,
        public ?string $lastname,
        public string $email,
        public bool $is_superadmin,
        public bool $is_accountant,
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
            id: Arr::get($data, 'id'),
            salutation_type: Arr::get($data, 'salutation_type'),
            firstname: Arr::get($data, 'firstname'),
            lastname: Arr::get($data, 'lastname'),
            email: Arr::get($data, 'email'),
            is_superadmin: Arr::get($data, 'is_superadmin'),
            is_accountant: Arr::get($data, 'is_accountant'),
        );
    }
}
