<?php

namespace CodebarAg\Bexio\Dto\Contacts;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ContactDTO extends Data
{
    public function __construct(
        public int $user_id, //ref to user
        public int $owner_id,
        public int $id,
        public int $nr,
        public int $contact_type_id,
        public string $name_1,
        public ?string $name_2,
        public ?int $salutation_id, //ref to salutation
        public ?int $salutation_form_id,
        public ?int $title_id, //ref to title
        public ?string $birthday,
        public ?string $address,
        public ?string $postcode,
        public ?string $city,
        public ?int $country_id, //ref to country
        public ?string $mail,
        public ?string $mail_second,
        public ?string $phone_fixed,
        public ?string $phone_fixed_second,
        public ?string $phone_mobile,
        public ?string $fax,
        public ?string $url,
        public ?string $skype_name,
        public ?string $remarks,
        public ?int $language_id, //ref to language
        public bool $is_lead,
        public ?string $contact_group_ids,
        public ?string $contact_branch_ids,
        public ?string $updated_at,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new \Exception('Failed to get all tickets', $response->status());
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
            nr: Arr::get($data, 'nr'),
            contact_type_id: Arr::get($data, 'contact_type_id'),
            name_1: Arr::get($data, 'name_1'),
            name_2: Arr::get($data, 'name_2'),
            salutation_id: Arr::get($data, 'salutation_id'),
            salutation_form_id: Arr::get($data, 'salutation_form_id'),
            title_id: Arr::get($data, 'title_id'),
            birthday: Arr::get($data, 'birthday'),
            address: Arr::get($data, 'address'),
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
            is_lead: Arr::get($data, 'is_lead'),
            contact_group_ids: Arr::get($data, 'contact_group_ids'),
            contact_branch_ids: Arr::get($data, 'contact_branch_ids'),
            user_id: Arr::get($data, 'user_id'),
            owner_id: Arr::get($data, 'owner_id'),
            updated_at: Arr::get($data, 'updated_At'),
        );
    }
}
