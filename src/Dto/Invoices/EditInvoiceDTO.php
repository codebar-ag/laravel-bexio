<?php

namespace CodebarAg\Bexio\Dto\Invoices;

use Exception;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

/**
 * DTO for editing an invoice (request payload).
 * Note: positions cannot be edited via the edit endpoint.
 */
class EditInvoiceDTO extends Data
{
    /**
     * Can not be used if “automatic numbering” is activated in frontend-settings. Required if “automatic numbering” deactivated.
     */
    public function __construct(
        public int $user_id,
        public int $language_id,
        public int $bank_account_id,
        public int $currency_id,
        public int $payment_type_id,
        public string $header,
        public string $footer,
        public int $mwst_type,
        public bool $mwst_is_net,
        public bool $show_position_taxes,
        public string $is_valid_from,
        public string $is_valid_to,
        public ?string $document_nr = null, // Can not be used if “automatic numbering” is activated in frontend-settings. Required if “automatic numbering” deactivated.
        public ?string $title = null,
        public ?int $contact_id = null,
        public ?int $contact_sub_id = null,
        public ?int $pr_project_id = null,
        /** @deprecated */
        public ?int $logopaper_id = null,
        public ?string $contact_address_manual = null,
        public ?string $reference = null,
        public ?string $api_reference = null,
        public ?string $template_slug = null,
    ) {}

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            user_id: Arr::get($data, 'user_id'),
            language_id: Arr::get($data, 'language_id'),
            bank_account_id: Arr::get($data, 'bank_account_id'),
            currency_id: Arr::get($data, 'currency_id'),
            payment_type_id: Arr::get($data, 'payment_type_id'),
            header: Arr::get($data, 'header'),
            footer: Arr::get($data, 'footer'),
            mwst_type: Arr::get($data, 'mwst_type'),
            mwst_is_net: Arr::get($data, 'mwst_is_net'),
            show_position_taxes: Arr::get($data, 'show_position_taxes'),
            is_valid_from: Arr::get($data, 'is_valid_from'),
            is_valid_to: Arr::get($data, 'is_valid_to'),
            document_nr: Arr::get($data, 'document_nr'),
            title: Arr::get($data, 'title'),
            contact_id: Arr::get($data, 'contact_id'),
            contact_sub_id: Arr::get($data, 'contact_sub_id'),
            pr_project_id: Arr::get($data, 'pr_project_id'),
            logopaper_id: Arr::get($data, 'logopaper_id'),
            contact_address_manual: Arr::get($data, 'contact_address_manual'),
            reference: Arr::get($data, 'reference'),
            api_reference: Arr::get($data, 'api_reference'),
            template_slug: Arr::get($data, 'template_slug'),
        );
    }
}
