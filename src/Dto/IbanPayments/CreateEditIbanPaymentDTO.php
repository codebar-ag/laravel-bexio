<?php

namespace CodebarAg\Bexio\Dto\IbanPayments;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class CreateEditIbanPaymentDTO extends Data
{

    public function __construct(
        public array $instructed_amount,
        public array $recipient,
        public string $iban,
        public string $execution_date,
        public string $is_salary_payment,
        public bool $is_editing_restricted,
        public string $message,
        public string $allowance_type,
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
            execution_date: Arr::get($data, 'execution_date'),
            is_salary_payment: Arr::get($data, 'is_salary_payment'),
            is_editing_restricted: Arr::get($data, 'is_editing_restricted'),
            message: Arr::get($data, 'message'),
            allowance_type: Arr::get($data, 'allowance_type'),
        );
    }
}
