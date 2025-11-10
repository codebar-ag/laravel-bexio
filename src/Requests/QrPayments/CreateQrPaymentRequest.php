<?php

namespace CodebarAg\Bexio\Requests\QrPayments;

use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Dto\QrPayments\CreateEditQrPaymentDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateQrPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $bank_account_id,
        public readonly array|CreateEditQrPaymentDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/bank_accounts/'.$this->bank_account_id.'/qr_payments';
    }

    public function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditQrPaymentDTO) {
            $body = CreateEditQrPaymentDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): PaymentDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return PaymentDTO::fromResponse($response);
    }
}
