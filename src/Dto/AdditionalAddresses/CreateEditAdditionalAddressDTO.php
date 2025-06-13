<?php

namespace CodebarAg\Bexio\Dto\AdditionalAddresses;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditAdditionalAddressDTO extends Data
{
    /**
     * DTO for creating/editing additional addresses. As of June 2025, use street_name, house_number, address_addition instead of address.
     */
    public function __construct(
        public string $name,
        public string $subject,
        public string $description,
        /** @deprecated Use street_name, house_number, address_addition instead. Will be removed after 2025-12-07. */
        public ?string $address = null,
        public ?string $street_name = null,
        public ?string $house_number = null,
        public ?string $address_addition = null,
        public ?string $postcode = null,
        public ?string $city = null,
        public ?string $country_id = null,
    ) {
        if ($this->address !== null) {
            $msg = 'The "address" property is deprecated and will be removed after 2025-12-07. Use street_name, house_number, address_addition instead.';
            logger()->warning($msg . " in " . __FILE__ . " on line " . __LINE__);
            trigger_error($msg, E_USER_DEPRECATED);
        }

        if (!$this->street_name && $this->address) {
            $this->street_name = $this->address;
            $this->address = null;
        }
    }

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

        $street_name = Arr::get($data, 'street_name');
        $address = Arr::get($data, 'address');

        if ($address !== null) {
            $msg = 'The "address" property is deprecated and will be removed after 2025-12-07. Use street_name, house_number, address_addition instead.';
            logger()->warning($msg . " in " . __FILE__ . " on line " . __LINE__);
            trigger_error($msg, E_USER_DEPRECATED);
        }

        if (!$street_name && $address) {
            $street_name = $address;
            $address = null;
        }

        return new self(
            name: Arr::get($data, 'name'),
            subject: Arr::get($data, 'subject'),
            description: Arr::get($data, 'description'),
            address: $address,
            street_name: $street_name,
            postcode: Arr::get($data, 'postcode'),
            city: Arr::get($data, 'city'),
            country_id: Arr::get($data, 'country_id'),
        );
    }
}
