<?php

namespace CodebarAg\Bexio\Dto\AdditionalAddresses;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditAdditionalAddressDTO extends Data
{
    public function __construct(
        public string $name,
        public string $subject,
        public string $description,
        public ?string $address = null,
        public ?int $postcode = null,
        public ?string $city = null,
        public ?string $country_id = null,
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
            subject: Arr::get($data, 'subject'),
            description: Arr::get($data, 'description'),
            address: Arr::get($data, 'address'),
            postcode: Arr::get($data, 'postcode'),
            city: Arr::get($data, 'city'),
            country_id: Arr::get($data, 'country_id'),
        );
    }
}
