<?php

namespace CodebarAg\Bexio\Dto\QrPayments;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditQrPaymentDTO extends Data
{

    public function __construct(
        public array $instructed_amount,
        public array $recipient,
        public string $iban,
        public string $qr_reference_nr,
        public string $additional_information,
        public bool $is_editing_restricted,
        public string $execution_date,
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
            instructed_amount: Arr::get($data, 'instructed_amount'),
            recipient: Arr::get($data, 'recipient'),
            iban: Arr::get($data, 'iban'),
            qr_reference_nr: Arr::get($data, 'qr_reference_nr'),
            additional_information: Arr::get($data, 'additional_information'),
            is_editing_restricted: Arr::get($data, 'is_editing_restricted'),
            execution_date: Arr::get($data, 'execution_date'),
        );
    }
}
