<?php

namespace CodebarAg\Bexio\Dto\AdditionalAddresses;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class AdditionalAddressDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $subject,
        public string $description,
        public ?string $address = null,
        public ?string $street_name = null,
        public ?string $house_number = null,
        public ?string $address_addition = null,
        public ?string $postcode = null,
        public ?string $city = null,
        public ?string $country_id = null,
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
            id: Arr::get($data, 'id'),
            name: Arr::get($data, 'name'),
            subject: Arr::get($data, 'subject'),
            description: Arr::get($data, 'description'),
            address: Arr::get($data, 'address'),
            street_name: Arr::get($data, 'street_name'),
            house_number: Arr::get($data, 'house_number'),
            address_addition: Arr::get($data, 'address_addition'),
            postcode: Arr::get($data, 'postcode'),
            city: Arr::get($data, 'city'),
            country_id: Arr::get($data, 'country_id'),
        );
    }
}
