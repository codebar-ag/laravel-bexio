<?php

namespace CodebarAg\Bexio\Dto\BankAccounts;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class BankAccountDTO extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $name,
        public string $owner,
        public string $owner_address,
        public string $owner_zip,
        public string $owner_city,
        public string $bc_nr,
        public string $bank_name,
        public string $bank_account_nr,
        public string $iban_nr,
        public int $currency_id,
        public int $account_id,
        public string $is_esr,
        public string $invoice_mode,
        public string $type,
        public string $bank_nr,
        public ?string $esr_besr_id = null,
        public ?string $esr_post_account_nr = null,
        public ?string $esr_payment_for_text = null,
        public ?string $esr_in_favour_of_text = null,
        public ?string $esr_bottom_line_include_amount = null,
        public ?string $remarks = null,
        public ?string $qr_invoice_iban = null,
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
            uuid: Arr::get($data, 'uuid'),
            name: Arr::get($data, 'name'),
            owner: Arr::get($data, 'owner'),
            owner_address: Arr::get($data, 'owner_address'),
            owner_zip: Arr::get($data, 'owner_zip'),
            owner_city: Arr::get($data, 'owner_city'),
            bc_nr: Arr::get($data, 'bc_nr'),
            bank_name: Arr::get($data, 'bank_name'),
            bank_account_nr: Arr::get($data, 'bank_account_nr'),
            iban_nr: Arr::get($data, 'iban_nr'),
            currency_id: Arr::get($data, 'currency_id'),
            account_id: Arr::get($data, 'account_id'),
            is_esr: Arr::get($data, 'is_esr'),
            invoice_mode: Arr::get($data, 'invoice_mode'),
            type: Arr::get($data, 'type'),
            bank_nr: Arr::get($data, 'bank_nr'),
            esr_besr_id: Arr::get($data, 'esr_besr_id'),
            esr_post_account_nr: Arr::get($data, 'esr_post_account_nr'),
            esr_payment_for_text: Arr::get($data, 'esr_payment_for_text'),
            esr_in_favour_of_text: Arr::get($data, 'esr_in_favour_of_text'),
            esr_bottom_line_include_amount: Arr::get($data, 'esr_bottom_line_include_amount'),
            remarks: Arr::get($data, 'remarks'),
            qr_invoice_iban: Arr::get($data, 'qr_invoice_iban'),
        );
    }
}
