<?php

namespace CodebarAg\Bexio\Dto\ContactAdditionalAddresses;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ContactAdditionalAddressDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $subject,
        public string $description,
        public string $address,
        public string $postcode,
        public string $city,
        public ?int $country_id = null,
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
            name: Arr::get($data, 'name'),
            subject: Arr::get($data, 'subject'),
            description: Arr::get($data, 'description'),
            address: Arr::get($data, 'address'),
            postcode: Arr::get($data, 'postcode'),
            city: Arr::get($data, 'city'),
            country_id: Arr::get($data, 'country_id')
        );
    }
}
