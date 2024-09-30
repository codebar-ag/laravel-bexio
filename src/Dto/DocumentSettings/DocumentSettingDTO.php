<?php

namespace CodebarAg\Bexio\Dto\DocumentSettings;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class DocumentSettingDTO extends Data
{
    public function __construct(
        public int $id,
        public string $text,
        public string $kb_item_class,
        public ?string $enumeration_format,
        public ?bool $user_automatic_enumeration,
        public ?bool $user_yearly_enumeration,
        public int $next_nr,
        public int $nr_min_length,
        public int $default_time_period_in_days,
        public int $default_logopaper_id,
        public int $default_language_id,
        public ?int $default_client_bank_account_new_id,
        public int $default_currency_id,
        public int $default_mwst_type,
        public bool $default_mwst_is_net,
        public int $default_nb_decimals_amount,
        public int $default_nb_decimals_price,
        public bool $default_show_position_taxes,
        public string $default_title,
        public bool $default_show_esr_on_same_page,
        public ?int $default_payment_type_id,
        public ?int $kb_terms_of_payment_template_id,
        public bool $default_show_total,
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
            text: Arr::get($data, 'text'),
            kb_item_class: Arr::get($data, 'kb_item_class'),
            enumeration_format: Arr::get($data, 'enumeration_format'),
            user_automatic_enumeration: Arr::get($data, 'user_automatic_enumeration'),
            user_yearly_enumeration: Arr::get($data, 'user_yearly_enumeration'),
            next_nr: Arr::get($data, 'next_nr'),
            nr_min_length: Arr::get($data, 'nr_min_length'),
            default_time_period_in_days: Arr::get($data, 'default_time_period_in_days'),
            default_logopaper_id: Arr::get($data, 'default_logopaper_id'),
            default_language_id: Arr::get($data, 'default_language_id'),
            default_client_bank_account_new_id: Arr::get($data, 'default_client_bank_account_new_id'),
            default_currency_id: Arr::get($data, 'default_currency_id'),
            default_mwst_type: Arr::get($data, 'default_mwst_type'),
            default_mwst_is_net: Arr::get($data, 'default_mwst_is_net'),
            default_nb_decimals_amount: Arr::get($data, 'default_nb_decimals_amount'),
            default_nb_decimals_price: Arr::get($data, 'default_nb_decimals_price'),
            default_show_position_taxes: Arr::get($data, 'default_show_position_taxes'),
            default_title: Arr::get($data, 'default_title'),
            default_show_esr_on_same_page: Arr::get($data, 'default_show_esr_on_same_page'),
            default_payment_type_id: Arr::get($data, 'default_payment_type_id'),
            kb_terms_of_payment_template_id: Arr::get($data, 'kb_terms_of_payment_template_id'),
            default_show_total: Arr::get($data, 'default_show_total'),
        );
    }
}
