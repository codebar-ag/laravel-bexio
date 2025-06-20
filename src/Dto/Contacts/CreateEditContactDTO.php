<?php

namespace CodebarAg\Bexio\Dto\Contacts;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditContactDTO extends Data
{
    /**
     * DTO for creating/editing contacts. As of June 2025, use street_name, house_number, address_addition instead of address.
     */
    public function __construct(
        public int $user_id, // ref to user
        public int $owner_id,
        public int $contact_type_id,
        public string $name_1,
        public ?string $name_2 = null,
        public ?int $salutation_id = null, // ref to salutation
        public ?int $salutation_form = null,
        public ?int $titel_id = null, // ref to title
        public ?Carbon $birthday = null,
        /** @deprecated Use street_name, house_number, address_addition instead. Will be removed after 2025-12-07. */
        public ?string $address = null,
        public ?string $street_name = null,
        public ?string $house_number = null,
        public ?string $address_addition = null,
        public ?string $postcode = null,
        public ?string $city = null,
        public ?int $country_id = null, // ref to country
        public ?string $mail = null,
        public ?string $mail_second = null,
        public ?string $phone_fixed = null,
        public ?string $phone_fixed_second = null,
        public ?string $phone_mobile = null,
        public ?string $fax = null,
        public ?string $url = null,
        public ?string $skype_name = null,
        public ?string $remarks = null,
        public ?int $language_id = null, // ref to language
        public ?string $contact_group_ids = null,
        public ?string $contact_branch_ids = null,
    ) {
        if ($this->address !== null) {
            $msg = 'The "address" property is deprecated and will be removed after 2025-12-07. Use street_name, house_number, address_addition instead.';
            logger()->warning($msg.' in '.__FILE__.' on line '.__LINE__);
            trigger_error($msg, E_USER_DEPRECATED);
        }

        if (! $this->street_name && $this->address) {
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
            logger()->warning($msg.' in '.__FILE__.' on line '.__LINE__);
            trigger_error($msg, E_USER_DEPRECATED);
        }

        if (! $street_name && $address) {
            $street_name = $address;
            $address = null;
        }

        return new self(
            user_id: Arr::get($data, 'user_id'),
            owner_id: Arr::get($data, 'owner_id'),
            contact_type_id: Arr::get($data, 'contact_type_id'),
            name_1: Arr::get($data, 'name_1'),
            name_2: Arr::get($data, 'name_2'),
            salutation_id: Arr::get($data, 'salutation_id'),
            salutation_form: Arr::get($data, 'salutation_form'),
            titel_id: Arr::get($data, 'title_id'),
            birthday: Arr::get($data, 'birthday'),
            address: $address,
            street_name: $street_name,
            house_number: Arr::get($data, 'house_number'),
            address_addition: Arr::get($data, 'address_addition'),
            postcode: Arr::get($data, 'postcode'),
            city: Arr::get($data, 'city'),
            country_id: Arr::get($data, 'country_id'),
            mail: Arr::get($data, 'mail'),
            mail_second: Arr::get($data, 'mail_second'),
            phone_fixed: Arr::get($data, 'phone_fixed'),
            phone_fixed_second: Arr::get($data, 'phone_fixed_second'),
            phone_mobile: Arr::get($data, 'phone_mobile'),
            fax: Arr::get($data, 'fax'),
            url: Arr::get($data, 'url'),
            skype_name: Arr::get($data, 'skype_name'),
            remarks: Arr::get($data, 'remarks'),
            language_id: Arr::get($data, 'language_id'),
            contact_group_ids: Arr::get($data, 'contact_group_ids'),
            contact_branch_ids: Arr::get($data, 'contact_branch_ids'),
        );
    }
}
