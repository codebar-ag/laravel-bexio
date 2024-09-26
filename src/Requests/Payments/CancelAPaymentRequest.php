<?php

namespace CodebarAg\Bexio\Requests\Payments;

use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class CancelAPaymentRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        readonly int|string $payment_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/payments/'.$this->payment_id.'/cancel';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return PaymentDTO::fromResponse($response);
    }
}
