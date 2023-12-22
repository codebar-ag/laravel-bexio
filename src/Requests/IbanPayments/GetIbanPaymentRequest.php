<?php

namespace CodebarAg\Bexio\Requests\IbanPayments;

use CodebarAg\Bexio\Dto\IbanPayments\IbanPaymentDTO;
use CodebarAg\Bexio\Dto\QrPayments\QrPaymentDTO;
use Exception;
use Faker\Calculator\Iban;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetIbanPaymentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $bank_account_id,
        readonly int $payment_id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/bank_accounts/'.$this->bank_account_id.'/iban_payments/'.$this->payment_id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return IbanPaymentDTO::fromResponse($response);
    }
}
