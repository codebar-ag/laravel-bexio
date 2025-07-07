<?php

namespace CodebarAg\Bexio\Dto\OpenID;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class UserInfoDTO extends Data
{
    public function __construct(
        public string $sub,
        public string $email,
        public bool $email_verified,
        public ?string $gender = null,
        public ?string $company_id = null,
        public ?string $company_name = null,
        public ?string $given_name = null,
        public ?string $locale = null,
        public ?int $company_user_id = null,
        public ?string $family_name = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new Exception('Failed to create DTO from Response');
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
            sub: Arr::get($data, 'sub'),
            email: Arr::get($data, 'email'),
            email_verified: Arr::get($data, 'email_verified'),
            gender: Arr::get($data, 'gender'),
            company_id: Arr::get($data, 'company_id'),
            company_name: Arr::get($data, 'company_name'),
            given_name: Arr::get($data, 'given_name'),
            locale: Arr::get($data, 'locale'),
            company_user_id: Arr::get($data, 'company_user_id'),
            family_name: Arr::get($data, 'family_name'),
        );
    }
}
