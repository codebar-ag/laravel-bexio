<?php

namespace CodebarAg\Bexio\Dto\Quotes;

use CodebarAg\Bexio\Dto\Invoices\InvoiceTaxDTO;
use CodebarAg\Bexio\Dto\ItemPositions\Abstractions\OfferPositionDTO;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class QuoteDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $document_nr,
        public ?string $title,
        public ?int $contact_id,
        public ?int $contact_sub_id,
        public int $user_id,
        public ?int $project_id,
        public ?int $pr_project_id,
        public ?int $logopaper_id, // Deprecated
        public ?int $language_id,
        public ?int $bank_account_id,
        public ?int $currency_id,
        public ?int $payment_type_id,
        public ?string $header,
        public ?string $footer,
        public ?string $total_gross,
        public ?string $total_net,
        public ?string $total_taxes,
        public ?string $total,
        public null|int|float $total_rounding_difference,
        public ?int $mwst_type,
        public ?bool $mwst_is_net,
        public ?bool $show_position_taxes,
        public ?string $is_valid_from,
        public ?string $is_valid_until,
        public ?string $contact_address,
        public ?string $contact_address_manual,
        public ?int $delivery_address_type,
        public ?string $delivery_address,
        public ?string $delivery_address_manual,
        public ?int $kb_item_status_id,
        public ?string $api_reference,
        public ?string $viewed_by_client_at,
        public ?int $kb_terms_of_payment_template_id,
        public ?bool $show_total,
        public ?string $updated_at,
        public ?string $template_slug,
        public ?Collection $taxs,
        public ?string $network_link,
        // Write-only: accepted when creating/editing a quote, not returned in list/detail responses.
        public ?Collection $positions = null,
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
            document_nr: Arr::get($data, 'document_nr'),
            title: Arr::get($data, 'title'),
            contact_id: Arr::get($data, 'contact_id'),
            contact_sub_id: Arr::get($data, 'contact_sub_id'),
            user_id: Arr::get($data, 'user_id'),
            project_id: Arr::get($data, 'project_id'),
            pr_project_id: Arr::get($data, 'pr_project_id'),
            logopaper_id: Arr::get($data, 'logopaper_id'),
            language_id: Arr::get($data, 'language_id'),
            bank_account_id: Arr::get($data, 'bank_account_id'),
            currency_id: Arr::get($data, 'currency_id'),
            payment_type_id: Arr::get($data, 'payment_type_id'),
            header: Arr::get($data, 'header'),
            footer: Arr::get($data, 'footer'),
            total_gross: Arr::get($data, 'total_gross'),
            total_net: Arr::get($data, 'total_net'),
            total_taxes: Arr::get($data, 'total_taxes'),
            total: Arr::get($data, 'total'),
            total_rounding_difference: Arr::get($data, 'total_rounding_difference'),
            mwst_type: Arr::get($data, 'mwst_type'),
            mwst_is_net: Arr::get($data, 'mwst_is_net'),
            show_position_taxes: Arr::get($data, 'show_position_taxes'),
            is_valid_from: Arr::get($data, 'is_valid_from'),
            is_valid_until: Arr::get($data, 'is_valid_until'),
            contact_address: Arr::get($data, 'contact_address'),
            contact_address_manual: Arr::get($data, 'contact_address_manual'),
            delivery_address_type: Arr::get($data, 'delivery_address_type'),
            delivery_address: Arr::get($data, 'delivery_address'),
            delivery_address_manual: Arr::get($data, 'delivery_address_manual'),
            kb_item_status_id: Arr::get($data, 'kb_item_status_id'),
            api_reference: Arr::get($data, 'api_reference'),
            viewed_by_client_at: Arr::get($data, 'viewed_by_client_at'),
            kb_terms_of_payment_template_id: Arr::get($data, 'kb_terms_of_payment_template_id'),
            show_total: Arr::get($data, 'show_total'),
            updated_at: Arr::get($data, 'updated_at'),
            template_slug: Arr::get($data, 'template_slug'),
            taxs: collect(Arr::get($data, 'taxs', []))->map(fn (array $tax) => InvoiceTaxDTO::fromArray($tax)),
            network_link: Arr::get($data, 'network_link'),
            positions: Arr::get($data, 'positions') !== null
                ? collect(Arr::get($data, 'positions'))->map(function (OfferPositionDTO|array $position) {
                    return $position instanceof OfferPositionDTO ? $position : OfferPositionDTO::fromArray($position);
                })
                : null,
        );
    }
}
