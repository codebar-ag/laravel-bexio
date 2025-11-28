<?php

namespace CodebarAg\Bexio\Dto\Invoices;

use CodebarAg\Bexio\Dto\ItemPositions\Abstractions\InvoicePositionDTO as NewInvoicePositionDTO;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class InvoiceDTO extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $document_nr,
        public ?string $title,
        public ?int $contact_id,
        public ?int $contact_sub_id,
        public int $user_id,
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
        public ?string $total_received_payments,
        public ?string $total_credit_vouchers,
        public ?string $total_remaining_payments,
        public ?string $total,
        public null|int|float $total_rounding_difference,
        public ?int $mwst_type,
        public ?bool $mwst_is_net,
        public ?bool $show_position_taxes,
        public ?string $is_valid_from,
        public ?string $is_valid_to,
        public ?string $contact_address,
        public ?int $kb_item_status_id,
        public ?string $reference,
        public ?string $api_reference,
        public ?string $viewed_by_client_at,
        public ?string $updated_at,
        public ?int $esr_id,
        public ?int $qr_invoice_id,
        public ?string $template_slug,
        public ?Collection $taxs,
        public ?string $network_link,
        public ?Collection $positions,
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
            document_nr: Arr::get($data, 'document_nr'),
            title: Arr::get($data, 'title'),
            contact_id: Arr::get($data, 'contact_id'),
            contact_sub_id: Arr::get($data, 'contact_sub_id'),
            user_id: Arr::get($data, 'user_id'),
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
            total_received_payments: Arr::get($data, 'total_received_payments'),
            total_credit_vouchers: Arr::get($data, 'total_credit_vouchers'),
            total_remaining_payments: Arr::get($data, 'total_remaining_payments'),
            total: Arr::get($data, 'total'),
            total_rounding_difference: Arr::get($data, 'total_rounding_difference'),
            mwst_type: Arr::get($data, 'mwst_type'),
            mwst_is_net: Arr::get($data, 'mwst_is_net'),
            show_position_taxes: Arr::get($data, 'show_position_taxes'),
            is_valid_from: Arr::get($data, 'is_valid_from'),
            is_valid_to: Arr::get($data, 'is_valid_to'),
            contact_address: Arr::get($data, 'contact_address'),
            kb_item_status_id: Arr::get($data, 'kb_item_status_id'),
            reference: Arr::get($data, 'reference'),
            api_reference: Arr::get($data, 'api_reference'),
            viewed_by_client_at: Arr::get($data, 'viewed_by_client_at'),
            updated_at: Arr::get($data, 'updated_at'),
            esr_id: Arr::get($data, 'esr_id'),
            qr_invoice_id: Arr::get($data, 'qr_invoice_id'),
            template_slug: Arr::get($data, 'template_slug'),
            taxs: collect(Arr::get($data, 'taxs', []))->map(fn (array $tax) => InvoiceTaxDTO::fromArray($tax)),
            network_link: Arr::get($data, 'network_link'),
            positions: collect(Arr::get($data, 'positions', []))
                ->map(function (InvoicePositionDTO|NewInvoicePositionDTO|array $tax) {
                    if ($tax instanceof InvoicePositionDTO || $tax instanceof NewInvoicePositionDTO) {
                        return $tax;
                    }

                    return InvoicePositionDTO::fromArray($tax);
                }),
        );
    }
}
