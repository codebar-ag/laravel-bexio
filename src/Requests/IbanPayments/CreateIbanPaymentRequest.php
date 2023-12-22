<?php

namespace CodebarAg\Bexio\Requests\IbanPayments;

use CodebarAg\Bexio\Dto\IbanPayments\CreateEditIbanPaymentDTO;
use CodebarAg\Bexio\Dto\IbanPayments\IbanPaymentDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateIbanPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $bank_account_id,
        readonly array|CreateEditIbanPaymentDTO $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/bank_accounts/'.$this->bank_account_id.'/iban_payments';
    }

    public function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditIbanPaymentDTO) {
            $body = CreateEditIbanPaymentDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return IbanPaymentDTO::fromResponse($response);
    }
}
