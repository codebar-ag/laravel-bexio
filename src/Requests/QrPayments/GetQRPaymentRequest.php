<?php

namespace CodebarAg\Bexio\Requests\QrPayments;

use CodebarAg\Bexio\Dto\Taxes\TaxDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetQRPaymentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $bank_account_id,
        readonly int $payment_id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/bank_accounts/'.$this->bank_account_id.'/qr_payments/'.$this->payment_id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return TaxDTO::fromResponse($response);
    }
}
