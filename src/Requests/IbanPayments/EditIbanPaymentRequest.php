<?php

namespace CodebarAg\Bexio\Requests\IbanPayments;

use CodebarAg\Bexio\Dto\IbanPayments\CreateEditIbanPaymentDTO;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditIbanPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        public readonly int $bank_account_id,
        public readonly int $payment_id,
        public readonly string $iban,
        public readonly int $id,
        public readonly array|CreateEditIbanPaymentDTO $data,
    ) {}

    public function defaultQuery(): array
    {
        return [
            'iban' => $this->iban,
            'id' => $this->id,
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/bank_accounts/'.$this->bank_account_id.'/iban_payments/'.$this->payment_id;
    }

    public function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditIbanPaymentDTO) {
            $body = CreateEditIbanPaymentDTO::fromArray($body);
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
