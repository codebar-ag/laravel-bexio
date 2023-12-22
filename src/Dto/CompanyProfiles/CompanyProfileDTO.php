<?php

namespace CodebarAg\Bexio\Dto\CompanyProfiles;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CompanyProfileDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $address,
        public int $postcode,
        public string $city,
        public int $country_id,
        public string $legal_form,
        public string $country_name,
        public string $phone_fixed,
        public bool $has_own_logo,
        public bool $is_public_profile,
        public bool $is_logo_public,
        public bool $is_address_public,
        public bool $is_phone_public,
        public bool $is_mobile_public,
        public bool $is_fax_public,
        public bool $is_mail_public,
        public bool $is_url_public,
        public bool $is_skype_public,
        public string $logo_base64,
        public ?string $address_nr = null,
        public ?string $mail = null,
        public ?string $phone_mobile = null,
        public ?string $fax = null,
        public ?string $url = null,
        public ?string $skype_name = null,
        public ?string $facebook_name = null,
        public ?string $twitter_name = null,
        public ?string $description = null,
        public ?string $ust_id_nr = null,
        public ?string $mwst_nr = null,
        public ?string $trade_register_nr = null,
    ) {
    }

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
            address: Arr::get($data, 'address'),
            postcode: Arr::get($data, 'postcode'),
            city: Arr::get($data, 'city'),
            country_id: Arr::get($data, 'country_id'),
            legal_form: Arr::get($data, 'legal_form'),
            country_name: Arr::get($data, 'country_name'),
            phone_fixed: Arr::get($data, 'phone_fixed'),
            has_own_logo: Arr::get($data, 'has_own_logo'),
            is_public_profile: Arr::get($data, 'is_public_profile'),
            is_logo_public: Arr::get($data, 'is_logo_public'),
            is_address_public: Arr::get($data, 'is_address_public'),
            is_phone_public: Arr::get($data, 'is_phone_public'),
            is_mobile_public: Arr::get($data, 'is_mobile_public'),
            is_fax_public: Arr::get($data, 'is_fax_public'),
            is_mail_public: Arr::get($data, 'is_mail_public'),
            is_url_public: Arr::get($data, 'is_url_public'),
            is_skype_public: Arr::get($data, 'is_skype_public'),
            logo_base64: Arr::get($data, 'logo_base64'),
            address_nr: Arr::get($data, 'address_nr'),
            mail: Arr::get($data, 'mail'),
            phone_mobile: Arr::get($data, 'phone_mobile'),
            fax: Arr::get($data, 'fax'),
            url: Arr::get($data, 'url'),
            skype_name: Arr::get($data, 'skype_name'),
            facebook_name: Arr::get($data, 'facebook_name'),
            twitter_name: Arr::get($data, 'twitter_name'),
            description: Arr::get($data, 'description'),
            ust_id_nr: Arr::get($data, 'ust_id_nr'),
            mwst_nr: Arr::get($data, 'mwst_nr'),
            trade_register_nr: Arr::get($data, 'trade_register_nr'),
        );
    }
}
